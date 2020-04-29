<?php

declare(strict_types=1);

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');

error_reporting(E_ALL);
date_default_timezone_set('Asia/Shanghai');

defined('BASE_PATH') or define('BASE_PATH', dirname(__DIR__));
defined('SWOOLE_HOOK_FLAGS') or define('SWOOLE_HOOK_FLAGS', SWOOLE_HOOK_ALL);

Swoole\Runtime::enableCoroutine(true);

require BASE_PATH . '/vendor/autoload.php';

$container = require BASE_PATH . '/bootstrap/app.php';

$container->get(Hyperf\Contract\ApplicationInterface::class);
