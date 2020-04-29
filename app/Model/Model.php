<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model as BaseModel;
use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;

/**
 * Class Model
 * @package App\Model
 */
abstract class Model extends BaseModel implements CacheableInterface
{
    use Cacheable;
}
