<?php
// DIC configuration

$container = $app->getContainer();


// Controller Deps

$container['\App\Controller\Turrent'] = function ($c) {
    $turrentstatus = new \App\Bluechilli\Turretstatus();
    return new \App\Controller\Turrent($turrentstatus);
};