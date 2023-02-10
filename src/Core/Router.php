<?php declare(strict_types=1);

namespace Frostnova\Core;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionException;

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

        $initialArguments = [$request, $response];

        $additionalArguments = [];

        foreach ($this->getArguments($controller, $method) as $argument) {
            $additionalArguments[] = $this->container->get($argument);
        }

        $arguments = $additionalArguments ? array_merge($initialArguments, $additionalArguments) : $initialArguments;

        return call_user_func_array([$controller, $method], $arguments);
    }

    /**
     * @throws ReflectionException
     */
    private function getArguments(object $controller, string $method): array
    {
        $reflection = new \ReflectionMethod($controller, $method);

        $result = [];

        foreach ($reflection->getParameters() as $param) {
            if (!in_array($param->getType()->getName(), ["Psr\Http\Message\RequestInterface", "Psr\Http\Message\ResponseInterface"])) {
                $result[] = $param->getType()->getName();
            }
        }

        return $result;
    }
}
