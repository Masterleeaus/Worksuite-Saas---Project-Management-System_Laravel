<?php

$routes = module_path('Newsletter', 'Routes/web.php');

if (file_exists($routes)) {
    require $routes;
}
