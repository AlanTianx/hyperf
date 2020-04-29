<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Hyperf\HttpServer\Contract\RequestInterface as HttpRequest;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class Auth
 * @package App\Http\Middleware
 */
class Auth implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var HttpRequest
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;

    /**
     * Auth constructor.
     * @param ContainerInterface $container
     * @param HttpRequest $request
     * @param HttpResponse $response
     */
    public function __construct(ContainerInterface $container, HttpRequest $request, HttpResponse $response)
    {
        $this->container = $container;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->hasHeader('token')) {
            return $this->response->json(['message' => 'auth middleware']);
        }

        return $handler->handle($request);
    }
}
