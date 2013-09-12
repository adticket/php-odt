#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/ExampleBuilder.php';

$outputDirInfo = new SplFileInfo(__DIR__ . '/output');
$exampleBuilder = new ExampleBuilder($outputDirInfo);
$exampleBuilder->build();
