<?php

namespace App\Module\Proxy;

use Hyperf\Guzzle\ClientFactory;

/**
 * Class ShippingProxy
 * @package App\Module\Proxy
 */
class ShippingProxy
{
    /**
     * @var ClientFactory
     */
    private $clientFactory;

    /**
     * ShippingProxy constructor.
     * @param ClientFactory $clientFactory
     */
    public function __construct(ClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    public function bar()
    {
        // $options 等同于 GuzzleHttp\Client 构造函数的 $config 参数
        $options = [];
        // $client 为协程化的 GuzzleHttp\Client 对象
        $client = $this->clientFactory->create($options);
    }

}
