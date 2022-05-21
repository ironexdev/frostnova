<?php declare(strict_types=1);

namespace Frostnova\Core;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Router implements MiddlewareInterface
{
    public function __construct(
        private ContainerInterface       $container,
        private ResponseFactoryInterface $responseFactory,
        private array                    $routes
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $this->responseFactory->createResponse();

        $resolver = $this->routes[$request->getUri()->getPath()][$request->getMethod()]["handler"] ?? null;

        if (!$resolver) {
            return $handler->handle($request);
        }

        list($controller, $method) = explode("::", $resolver);

        $controller = $this->container->get($controller);

        return call_user_func([$controller, $method], $request, $response);
    }
}
