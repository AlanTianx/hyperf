<?php

declare(strict_types=1);

namespace App\Controller;

/**
 * Class IndexController
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    /**
     * @param string|null $path
     * @return mixed
     */
    public function view(string $path = null)
    {
        return $this->render('welcome', [
            'path' => $path
        ]);
    }
}
