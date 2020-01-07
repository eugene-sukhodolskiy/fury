<?php

use Fury\Kernel\Router;

$router = new Router();

$router -> get(['var1', 'var2'], '\TestApp\Welcome@hello');
$router -> uri('/testing', '\TestApp\Welcome@testing');
$router -> uri('/id/$id/$post', '\TestApp\Welcome@testing_with_params');

$router -> start_routing();