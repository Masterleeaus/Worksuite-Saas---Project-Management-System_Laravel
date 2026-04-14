<?php

$routes = module_path('Testimonials', 'Routes/api.php');

if (file_exists($routes)) {
    require $routes;
}
