<?php

$routes = module_path('Newsletter', 'Routes/api.php');

if (file_exists($routes)) {
    require $routes;
}
