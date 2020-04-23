<?php

namespace App\Module\User\Service;

use App\Module\User\Model\User;

/**
 * Class UserService
 * @package App\Module\User\Service
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
