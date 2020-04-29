<?php

declare(strict_types=1);

namespace App\Service\Address;

use App\Service\Address\Model\Address;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Model;

/**
 * Class AddressService
 * @package App\Service\Address
 */
class AddressService
{
    /**
     * @param int $id
     * @return Builder[]|Collection
     */
    public function getListById(int $id)
    {
        return Address::query()->where('user_id', $id)->get();
    }

    /**
     * @param int $id
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function getAddressById(int $id)
    {
        return Address::query()->find($id);
    }
}
