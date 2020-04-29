<?php

declare(strict_types=1);

namespace App\Http\Controller\Auth;

use App\Http\Controller\AbstractController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 * @Controller
 * Class LoginController
 * @package App\Http\Controller\Auth
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
