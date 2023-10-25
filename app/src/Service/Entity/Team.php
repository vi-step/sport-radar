<?php

namespace App\Service\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: "App\Service\Repository\TeamRepository")]
#[ORM\Table(name: 'teams')]
class Team
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\Length(max: 50)]
    public string $name;

    public bool $isNew = false;

    public function __construct(string $teamName)
    {
        $this->name = $teamName;
        $this->isNew = true;
    }
}
