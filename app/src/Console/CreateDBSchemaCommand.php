<?php

namespace App\Console;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDBSchemaCommand extends Command
{
    public function __construct(private readonly EntityManager $entityManager)
    {
        parent::__construct('create-db-schema');
        $this->setDescription('Creates DB schema');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $tool = new SchemaTool($this->entityManager);
        $tool->createSchema($metadata);
        $output->writeln("<info>Schema created!</info>");
        return Command::SUCCESS;
    }

}
