<?php

// Throwaway Route
$app->get('/', '\App\Controller\Root:Root');

// For the turret to check
$app->get('/turret/check', '\App\Controller\Turret:Check');
