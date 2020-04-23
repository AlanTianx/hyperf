<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\Contract\SessionInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\View\RenderInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * Class AbstractController
 * @package App\Controller
 */
abstract class AbstractController
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @Inject
     * @var SessionInterface
     */
    protected $session;

    /**
     * @Inject
     * @var RenderInterface
     */
    protected $view;

    /**
     * @var int
     */
    private $errorCode = 1;

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    /**
     * @param int $errorCode
     */
    public function setErrorCode(int $errorCode): void
    {
        $this->errorCode = $errorCode;
    }

    /**
     * 返回封装后的API数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param array $headers 发送的Header信息
     * @return PsrResponseInterface
     */
    protected function succeed($data, array $headers = [])
    {
        $response = $this->response([
            'status' => 'success',
            'data' => $data,
            'time' => time(),
        ]);

        foreach ($headers as $header) {
            $response = $response->withHeader(key($header), current($header));
        }

        return $response;
    }

    /**
     * 返回异常数据到客户端
     * @param $message
     * @return PsrResponseInterface
     */
    protected function failed($message)
    {
        return $this->response([
            'status' => 'failed',
            'errors' => [
                'code' => $this->getErrorCode(),
                'message' => $message,
            ],
            'time' => time(),
        ]);
    }

    /**
     * 返回 Json 数据格式
     * @param $data
     * @return PsrResponseInterface
     */
    protected function response($data)
    {
        $name = 'x-client-id';

        // 客户端设备唯一ID
        $client_id = $this->request->header($name);

        if (is_null($client_id) || empty($client_id)) {
            $client_id = $this->session->getId();
        }

        return $this->response->json($data)->withHeader($name, $client_id);
    }

    /**
     * The view to render
     * @param $template
     * @param array $data
     * @return mixed
     */
    protected function render($template, array $data = [])
    {
        return $this->view->render($template, $data);
    }
}
