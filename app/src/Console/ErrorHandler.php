<?php

namespace App\Console;

use App\Service\Error\DisplayableExceptionInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class ErrorHandler
{
    public function __construct(
        private readonly bool $debug
    )
    {
    }

    public function handle(callable $callback, OutputInterface $output, &$result): bool
    {
        try {
            $result = $callback();
        } catch (Throwable $e) {
            $message = $e instanceof DisplayableExceptionInterface
                ? $e->getMessage()
                : ($this->debug ? $e : 'Internal Error');
            $output->writeln("<error> $message </error>");
            return false;
        }
        return true;
    }
}
