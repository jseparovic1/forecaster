<?php

declare(strict_types=1);

use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;

require 'vendor/autoload.php';

$container = require 'config/container.php';
$console = $container->get('config')['console'];

$application = new Application($console['name']);
$application->setCommandLoader(new ContainerCommandLoader($container, $console['commands']));
$application->run();
