<?php
namespace App\Entity;
namespace App\Service\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: "App\Service\Repository\GameRepository")]
#[ORM\Table(name: 'games')]
class Game
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\ManyToOne(targetEntity: Team::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'home_team_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    public $homeTeam;

    #[ORM\ManyToOne(targetEntity: Team::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'away_team_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    public $awayTeam;

    #[ORM\Column(type: 'integer')]
    #[Assert\Range(min: 0, max: 100)]
    public int $homeScore = 0;

    #[ORM\Column(type: 'integer')]
    #[Assert\Range(min: 0, max: 100)]
    public int $awayScore = 0;

    #[ORM\Column(type: 'datetime')]
    public \DateTime $startTime;

    public function __construct(Team $homeTeam, Team $awayTeam)
    {
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->startTime = new \DateTime();
    }
}
