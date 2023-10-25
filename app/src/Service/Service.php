<?php

namespace App\Service;

use App\Service\Entity\Game;
use App\Service\Entity\Team;
use App\Service\Repository\GameRepository;
use App\Service\Repository\TeamRepository;

class Service
{
    public function __construct(
        private readonly TeamRepository $teamRepository,
        private readonly GameRepository $gameRepository,
        private readonly Validator      $validator,
    )
    {
    }

    private function requireGame(string $homeTeamName, string $awayTeamName): Game
    {
        $this->validator->check(
            $game = $this->gameRepository->findByTeams($homeTeamName, $awayTeamName),
            "Game between $homeTeamName and $awayTeamName does not exist"
        );
        return $game;
    }

    public function start(string $homeTeamName, string $awayTeamName): Game
    {
        $this->validator->checkAll([
            [$homeTeamName !== $awayTeamName, 'Team cannot play against itself'],
            [$this->teamRepository->count(['name' => $homeTeamName]) === 0, "Team $homeTeamName already plays"],
            [$this->teamRepository->count(['name' => $awayTeamName]) === 0, "Team $awayTeamName already plays"],
        ]);
        return $this->gameRepository->save(new Game(
            homeTeam: new Team($homeTeamName),
            awayTeam: new Team($awayTeamName)
        ));
    }

    public function finish(string $homeTeamName, string $awayTeamName): void
    {
        $game = $this->requireGame($homeTeamName, $awayTeamName);
        $this->gameRepository->delete($game);
    }

    public function update(string $homeTeamName, string $awayTeamName, int $homeScore, int $awayScore): Game
    {
        $game = $this->requireGame($homeTeamName, $awayTeamName);
        $this->validator->checkAll([
            [$homeScore >= 0, 'Home score cannot be negative'],
            [$awayScore >= 0, 'Away score cannot be negative'],
            [$homeScore >= $game->homeScore, 'Decreasing score is not allowed'],
            [$awayScore >= $game->homeScore, 'Decreasing score is not allowed'],
            [$awayScore !== $game->awayScore || $homeScore != $game->homeScore, 'Nothing changed'],
        ]);
        $game->homeScore = $homeScore;
        $game->awayScore = $awayScore;
        $this->gameRepository->save($game);
        return $game;
    }

    public function reset(): void
    {
        $this->gameRepository->deleteAll();
        $this->teamRepository->deleteAll();
    }

    /**
     * @return Game[]
     */
    public function summary(): array
    {
        return $this->gameRepository->getAllByTotals();
    }
}
