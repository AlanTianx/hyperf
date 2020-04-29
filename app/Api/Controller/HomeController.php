<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Controller\AbstractController;
use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\HttpServer\Annotation\AutoController;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * @AutoController(prefix="api/home")
 * Class HomeController
 * @package App\Api\Controller
 */
class HomeController extends AbstractController
{
    /**
     * @Cacheable(prefix="test", group="co")
     * @return PsrResponseInterface
     */
    public function index()
    {
        return $this->succeed(['error' => 0, 'message' => 'ok']);
    }
}
