<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Model\Model;

/**
 * Class User
 * @package App\Service\User
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
