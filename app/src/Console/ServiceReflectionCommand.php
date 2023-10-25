<?php

namespace App\Console;

use App\Service\Error\DisplayableExceptionInterface;
use App\Service\Error\ServiceError;
use App\Console\ErrorHandler;
use App\Service\Service;
use Nayjest\StrCaseConverter\Str;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class ServiceReflectionCommand extends Command
{
    protected static $defaultName = null;
    public function __construct(
        private readonly ReflectionMethod $serviceMethod,
        private readonly object $service,
        private readonly ErrorHandler $errorHandler = new ErrorHandler(true),
        /** @callable */
        private $viewFunction = null
    )
    {
        parent::__construct($serviceMethod->getName());
        $this->setDescription(ucfirst($serviceMethod->getName()) . ' Game');
        foreach ($serviceMethod->getParameters() as $param) {
            $this->addArgument($param->getName(), InputArgument::REQUIRED, Str::toCamelCase($param->getName()));
        }
    }

    /**
     * @throws ServiceError
     */
    protected function getFromInput(ReflectionParameter $param, InputInterface $input): string|int
    {
        $value = $input->getArgument($param->getName());
        if ($param->getType()->getName() === 'int') {
            if (!is_numeric($value)) {
                throw new ServiceError("{$param->getName()} must be numeric");
            }
            $value = (int)$value;
        }
        return $value;
    }

    /**
     * @throws Throwable
     */
    protected function invokeService(InputInterface $input): mixed
    {
        $args = array_map(fn($param) => $this->getFromInput($param, $input), $this->serviceMethod->getParameters());
        return $this->serviceMethod->invoke($this->service, ...$args);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->viewFunction) {
            $output->writeln(sprintf('<info>%s</info>', ucwords("{$this->getName()}ing game...")));
        }
        $result = null;
        $success = $this->errorHandler->handle(fn() => $this->invokeService($input), $output, $result);
        if (!$success) {
            return Command::FAILURE;
        }
        if ($this->viewFunction) {
            ($this->viewFunction)($result, $output);
        } else {
            $output->writeln(sprintf('<info>%s</info>', ucwords("game successfully {$this->getName()}ed")));
        }
        return Command::SUCCESS;
    }
}
