<?php

namespace App\Service\User\Service;

use App\Service\User\Model\User;

/**
 * Class UserService
 * @package App\Service\User\Service
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
