<?php

// Throwaway Route
$app->get('/', '\App\Controller\Root:Root');

// For the turret to check
$app->get('/turret/check', '\App\Controller\Turret:Check');

// For webhooks
$app->post('/webhooks/slack', '\App\Controller\Slack:Webhook');
//$app->post('/webhooks/teamcity', '\App\Controller\TeamCity:Webhook');


