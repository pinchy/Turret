<?php
// DIC configuration

$container = $app->getContainer();


// Controller Deps

$container['\App\Controller\Turret'] = function ($c) {
    $FireOrder = new \App\Bluechilli\FireOrder();
    return new \App\Controller\Turret($FireOrder);
};