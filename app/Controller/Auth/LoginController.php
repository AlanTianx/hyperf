<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Controller\AbstractController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 * @Controller
 * Class LoginController
 * @package App\Controller\Auth
 */
class LoginController extends AbstractController
{
    /**
     * @GetMapping("/login")
     * @return mixed
     */
    public function index()
    {
        return $this->render('auth.login');
    }

    /**
     * @PostMapping("/login")
     * @return array
     */
    public function handle()
    {
        return [
            'method' => 'handle',
            'message' => 'Login',
        ];
    }
}
