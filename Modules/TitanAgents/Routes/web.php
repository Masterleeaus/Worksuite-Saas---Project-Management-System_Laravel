<?php

$routes = module_path('TitanAgents', 'Routes/web.php');

if (file_exists($routes)) {
    require_once $routes;
}
