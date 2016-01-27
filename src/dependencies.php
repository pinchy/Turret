<?php
// DIC configuration

$container = $app->getContainer();


// Controller Deps

$container['\App\Controller\Turret'] = function ($c) {
    $FireOrder = new \App\Bluechilli\FireOrder();
    return new \App\Controller\Turret($FireOrder);
};


$container['\App\Controller\Slack'] = function ($c) {
    $FireOrder = new \App\Bluechilli\FireOrder();
    return new \App\Controller\Slack($FireOrder);
};


// Just an example on how the config works.
$container['\App\Controller\Root'] = function ($c) {

    // This container is created in /public/index.php, but it could be done
    // in this file as $container["config"] =...

    $config = $c->get("config");
    $turretConfig = $c->get("config")["turret"];
    $foo = $config["turret"]["foo"];

    return new \App\Controller\Root($config);
};