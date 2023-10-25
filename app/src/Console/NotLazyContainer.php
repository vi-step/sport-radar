<?php

namespace App\Console;

use App\Service\Entity\Game;
use App\Service\Entity\Team;
use App\Service\Service;
use App\Service\Validator;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Console\Application;

class NotLazyContainer
{
    public readonly EntityManager $entityManager;
    public readonly Connection $connection;
    public readonly Service $service;
    public readonly ErrorHandler $errorHandler;

    public function __construct(
        public readonly array $config
    )
    {
        $this->setupDoctrine();
        $this->setupService();

    }

    private function setupDoctrine(): void
    {
        $attributeMetadataConfiguration = ORMSetup::createAttributeMetadataConfiguration(
            paths: array($_ENV['APP_DIR'] . '/src/Service/Entity'),
            isDevMode: true,
        );
        $this->connection = DriverManager::getConnection($this->config['database'], $attributeMetadataConfiguration);
        $this->entityManager = new EntityManager($this->connection, $attributeMetadataConfiguration);
    }

    private function setupService(): void
    {
        /**
         * @var \App\Service\Repository\GameRepository $gameRepository
         * @var \App\Service\Repository\TeamRepository $teamRepository
         */
        $teamRepository = $this->entityManager->getRepository(Team::class);
        $gameRepository = $this->entityManager->getRepository(Game::class);
        $this->service = new Service($teamRepository, $gameRepository, new Validator());
    }

    public function consoleApp()
    {
        $app = new Application();
        (new ReflectionCommandsBuilder())->buildCommands(
            $app,
            $this->service,
            new ErrorHandler($this->config['debug']),
            ['summary' => new SummaryView]
        );
        $app->add(new CreateDBSchemaCommand($this->entityManager));
        return $app;
    }

}
