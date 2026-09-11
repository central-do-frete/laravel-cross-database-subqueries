<?php

$loader = require (getenv('FORK_TEST_AUTOLOAD') ?: dirname(__DIR__).'/vendor/autoload.php');
$loader->addPsr4('Hoyvoy\\CrossDatabase\\', dirname(__DIR__).'/src', true);
$loader->addPsr4('Hoyvoy\\Tests\\', __DIR__, true);
