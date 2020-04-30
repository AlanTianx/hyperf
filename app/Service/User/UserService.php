<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Service\User\User;

/**
 * Class UserService
 * @package App\Service\User
 */
class UserService
{
    /**
     * @var User
     */
    private $user;

    /**
     * UserService constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
