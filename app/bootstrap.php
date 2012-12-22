<?php

use \Nette\Application\Routers\Route;

// Load Nette Framework
require LIBS_DIR . '/nette/loader.php';


// Configure application
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(__DIR__ . '/../temp');


// Enable Nette Debugger for error visualisation & logging
$configurator->enableDebugger(__DIR__ . '/../log');


// Enable RobotLoader - this will load all classes automatically
$configurator->createRobotLoader()
    ->addDirectory(__DIR__)
    ->addDirectory(LIBS_DIR)
    ->register();


// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/config.neon');
$container = $configurator->createContainer();


// Database connect
dibi::connect($container->parameters['db']);

// Setup router
$uri = $container->parameters['productionMode'] ? 'example/' : '';
$container->router[] = new Route("$uri<filterRenderType>/<action>/", array(
    'presenter' => 'Example',
    'action' => 'default',
    'filterRenderType' => 'inner'
));

// Run the application!
$container->application->run();
