<?php

$routes = module_path('Testimonials', 'Routes/web.php');

if (file_exists($routes)) {
    require $routes;
}
