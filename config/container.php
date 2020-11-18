<?php

declare(strict_types=1);

use Laminas\ServiceManager\ServiceManager;

$config = require 'config/config.php';

$dependencies = $config['dependencies'];
$dependencies['services']['config'] = $config;

return new ServiceManager($dependencies);
