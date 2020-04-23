<?php

declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST'], '/[{path}]', 'App\Controller\IndexController@view');
