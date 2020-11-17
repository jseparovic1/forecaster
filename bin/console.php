<?php

declare(strict_types=1);

use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;

require 'vendor/autoload.php';

$container = require 'config/container.php';
$commands = $container->get('config')['commands'];

$application = new Application();
$application->setCommandLoader(new ContainerCommandLoader($container, $commands));
$application->run();
