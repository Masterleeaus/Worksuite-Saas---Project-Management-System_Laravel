<?php

$routes = module_path('TitanZero', 'Routes/web.php');

if (file_exists($routes)) {
    require $routes;
}
