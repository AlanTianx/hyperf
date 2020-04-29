<?php

declare(strict_types=1);

namespace App\Http\Controller\Web;

use App\Http\Controller\AbstractController;
use Hyperf\Contract\TranslatorInterface;
use Hyperf\Di\Annotation\Inject;

/**
 * Class IndexController
 * @package App\Http\Controller\Web
 */
class IndexController extends AbstractController
{
    /**
     * @Inject
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param string|null $path
     * @return mixed
     */
    public function view(string $path = null)
    {
        $acceptLanguage = $this->request->header('Accept-Language', 'en');
        if ($acceptLanguage) {
            $locale = explode(',', $acceptLanguage)[0];
            $this->translator->setLocale(str_replace('-', '_', $locale));
        }

        return $this->render('welcome', [
            'message' => trans('messages.welcome'),
            'path' => $path
        ]);
    }
}
