<?php

declare(strict_types=1);

use Hyperf\View\Engine\BladeEngine;
use Hyperf\View\Mode;

return [
    'engine' => BladeEngine::class,
    'mode' => Mode::TASK,
    'config' => [
        'view_path' => BASE_PATH . '/resources/views/',
        'cache_path' => BASE_PATH . '/runtime/views/',
    ],
];
