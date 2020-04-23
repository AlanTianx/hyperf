<?php

declare(strict_types=1);

namespace App\Module\Address\Model;

use App\Model\Model;

/**
 * Class Address
 * @package App\Module\Address\Model
 */
class Address extends Model
{
    /**
     * @var string
     */
    protected $table = 'address';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

}
