<?php

declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;

Router::get('/[{path}]', 'App\Http\Controller\Web\IndexController@view');
