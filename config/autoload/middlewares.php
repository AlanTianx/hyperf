<?php

declare(strict_types=1);

return [
    'http' => [
        \Hyperf\Session\Middleware\SessionMiddleware::class,
        \Hyperf\Validation\Middleware\ValidationMiddleware::class,
    ],
];
