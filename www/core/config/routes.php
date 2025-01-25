<?php

$routesFile = __DIR__ . '/../../routes.yaml';
$routes = yaml_parse_file($routesFile);

return $routes;