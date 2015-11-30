<?php
use Cake\Routing\Router;

Router::plugin('Altair', function ($routes) {
    $routes->fallbacks('DashedRoute');
});
