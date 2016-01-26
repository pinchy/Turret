<?php

// Throwaway Route
$app->get('/', '\App\Controller\Root:Root');

// For the turrent to check
$app->get('/turrent/check', '\App\Controller\Turrent:Check');
