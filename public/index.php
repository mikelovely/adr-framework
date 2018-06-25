<?php

require '../vendor/autoload.php';

define("APP_PATH", realpath(dirname(dirname(__FILE__))));

$factory = new \Adr\AppFactory;

$app = $factory->make();

$app->run();
