<?php

declare(strict_types=1);

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');

error_reporting(E_ALL);
date_default_timezone_set('UTC');

!defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__));
!defined('SWOOLE_HOOK_FLAGS') && define('SWOOLE_HOOK_FLAGS', SWOOLE_HOOK_ALL);

Swoole\Runtime::enableCoroutine(true);

require BASE_PATH . '/vendor/autoload.php';

$container = require BASE_PATH . '/bootstrap/app.php';

$container->get(Hyperf\Contract\ApplicationInterface::class);
