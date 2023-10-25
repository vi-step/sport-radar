<?php

namespace App\Console;

use Symfony\Component\Console\Application;
use ReflectionClass;
use ReflectionMethod;

class ReflectionCommandsBuilder
{
    /**
     * @param \Symfony\Component\Console\Application $app
     * @param object $service
     * @param \App\Console\ErrorHandler $errorHandler
     * @param callable[] $customViews
     * @return \Symfony\Component\Console\Application
     */
    public function buildCommands(
        Application  $app,
        object       $service,
        ErrorHandler $errorHandler,
        array        $customViews = []
    ): Application
    {
        $serviceRef = new ReflectionClass($service);
        foreach ($serviceRef->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (strpos($method->getName(), '_')) {
                continue;
            }
            $app->add(new ServiceReflectionCommand(
                $method,
                $service,
                $errorHandler,
                array_key_exists($method->getName(), $customViews) ? $customViews[$method->getName()] : null
            ));
        }
        return $app;
    }
}
