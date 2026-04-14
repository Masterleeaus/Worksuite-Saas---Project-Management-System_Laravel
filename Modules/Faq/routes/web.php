<?php

$routes = module_path('Faq', 'Routes/web.php');

if (file_exists($routes)) {
    require $routes;
}
