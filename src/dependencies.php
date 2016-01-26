<?php
// DIC configuration

$container = $app->getContainer();


// Controller Deps

$container['\App\Controller\Turret'] = function ($c) {
    $FireCommand = new \App\Bluechilli\FireCommand();
    return new \App\Controller\Turret($FireCommand);
};