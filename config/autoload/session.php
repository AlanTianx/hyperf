<?php

declare(strict_types=1);

use Hyperf\Session\Handler;

return [
    'handler' => Handler\FileHandler::class,
    'options' => [
        'connection' => 'default',
        'path' => BASE_PATH . '/runtime/session',
        'gc_maxlifetime' => 1440,
        'session_name' => 'SESSION_ID',
    ],
];
