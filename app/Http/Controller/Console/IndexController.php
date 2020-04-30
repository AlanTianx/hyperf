<?php

declare(strict_types=1);

namespace App\Http\Controller\Console;

use App\Http\Controller\AbstractController;
use Hyperf\Contract\TranslatorInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

/**
 * @Controller
 * Class IndexController
 * @package App\Http\Controller\Console
 */
class IndexController extends AbstractController
{
    /**
     * @GetMapping("/admin")
     * @return mixed
     */
    public function index()
    {
        return $this->render('console.index');
    }
}
