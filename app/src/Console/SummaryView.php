<?php

namespace App\Console;


use App\Service\Entity\Game;
use Symfony\Component\Console\Output\Output;

final class SummaryView
{
    public function __invoke(array $games, Output $output): void
    {
        $output->writeln('=== [<info>Games</info>] ===');
        foreach ($games as $game) {
            $output->writeln($this->getScoreLine($game));
        }
    }

    private function getScoreLine(Game $game): string
    {
        return "<info>{$game->homeTeam->name} vs {$game->awayTeam->name}</info>\t{$game->homeScore}:{$game->awayScore}";
    }
}
