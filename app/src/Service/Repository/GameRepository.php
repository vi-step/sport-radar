<?php

namespace App\Service\Repository;

use App\Service\Entity\Game;

class GameRepository extends BaseRepository
{
    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByTeams(string $homeTeamName, string $awayTeamName): Game|null
    {
        return $this->createQueryBuilder('g')
            ->innerJoin('g.homeTeam', 'homeTeam')
            ->innerJoin('g.awayTeam', 'awayTeam')
            ->where('homeTeam.name = :homeTeamName')
            ->andWhere('awayTeam.name = :awayTeamName')
            ->setParameter('homeTeamName', $homeTeamName)
            ->setParameter('awayTeamName', $awayTeamName)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Game[]
     */
    public function getAllByTotals(): array
    {
        return $this
            ->createQueryBuilder('g')
            ->select('g')
            ->orderBy('g.homeScore + g.awayScore', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
