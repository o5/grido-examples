<?php

require __DIR__ . '/../libs/autoload.php';

// Configure application
$configurator = new \Nette\Configurator;

// Enable Nette Debugger for error visualisation & logging
//$configurator->setDebugMode();
//$configurator->setDebugMode(FALSE);
 $configurator->enableDebugger(__DIR__ . '/../log');

// Specify folder for cache
$configurator->setTempDirectory(__DIR__ . '/../temp');

// Enable RobotLoader - this will load all classes automatically
$loader = $configurator->createRobotLoader()
    ->addDirectory(__DIR__)
    ->register();

// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/config.neon');
$container = $configurator->createContainer();

// Setup router
$uri = BasePresenter::getExtraPath();
$container->router[] = new \Nette\Application\Routers\Route("$uri<filterRenderType>/<presenter>/<action>/<ajax>/", array(
    'filterRenderType' => 'inner',
    'presenter' => 'NetteDatabase',
    'action' => 'default',
    'ajax' => 'on',
));

return $container;
