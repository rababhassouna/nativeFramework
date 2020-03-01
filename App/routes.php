<?php
use System\Application;

$app = Application::getInstance();

$app->route->add('/post/tes/1','BlogController','GET');
