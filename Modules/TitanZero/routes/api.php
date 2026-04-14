<?php

$routes = module_path('TitanZero', 'Routes/api.php');

if (file_exists($routes)) {
    require $routes;
}
