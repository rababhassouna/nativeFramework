<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/System/Application.php';
require __DIR__ . '/../vendor/System/File.php';

$file = new \System\File(dirname(__DIR__));
$app = new \System\Application($file);
$app->run();
