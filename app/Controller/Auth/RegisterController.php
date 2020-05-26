<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Controller\AbstractController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 * @Controller
 * Class RegisterController
 * @package App\Controller\Auth
 */
class RegisterController extends AbstractController
{
    /**
     * @GetMapping("/register")
     * @return mixed
     */
    public function index()
    {
        return $this->render('auth.register');
    }

    /**
     * @PostMapping("/register")
     * @return array
     */
    public function handle()
    {
        return [
            'method' => 'handle',
            'message' => 'Register',
        ];
    }
}
