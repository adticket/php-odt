<?php

defined('ROOT_DIR') || define('ROOT_DIR', realpath(__DIR__ . '/..'));

/** @var $loader \Composer\Autoload\ClassLoader */
$loader = require __DIR__ . '/../vendor/autoload.php';

$loader->add('OdtCreator\\Test\\', __DIR__);
