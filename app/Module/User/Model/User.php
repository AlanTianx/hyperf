<?php

declare(strict_types=1);

namespace App\Module\User\Model;

use App\Model\Model;

/**
 * Class User
 * @package App\Module\User\Model
 */
class User extends Model
{
    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $hidden = [
        'password',
    ];
}
