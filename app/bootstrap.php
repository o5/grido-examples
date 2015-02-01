<?php

require __DIR__ . '/../vendor/autoload.php';

// Configure application
$configurator = new \Nette\Configurator;

// Enable Nette Debugger for error visualisation & logging
//$configurator->setDebugMode(FALSE);
$configurator->enableDebugger(__DIR__ . '/../log');

// Specify folder for cache
$configurator->setTempDirectory(__DIR__ . '/../temp');

// Enable RobotLoader - this will load all classes automatically
$loader = $configurator->createRobotLoader()
    ->addDirectory(__DIR__)
    ->register();

// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/config/config.neon');
$container = $configurator->createContainer();

return $container;
