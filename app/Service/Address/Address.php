<?php

declare(strict_types=1);

namespace App\Service\Address;

use App\Model\Model;

/**
 * Class Address
 * @package App\Service\Address
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
