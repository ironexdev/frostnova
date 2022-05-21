<?php

namespace Frostnova\Api;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

abstract class AbstractController
{
    protected string $defaultResponseContentType = "application/json";

    public function __construct(
        private StreamFactoryInterface $streamFactory
    )
    {
    }

    protected function response(
        object            $parameters,
        RequestInterface  $request,
        ResponseInterface $response
    ): ResponseInterface
    {
        if (!$response->getHeaderLine("Content-Type")) {
            $response = $response->withHeader(
                "Content-Type",
                $request->getHeaderLine("Accept") ?? $this->defaultResponseContentType
            ); // If Response Content-Type is not set then try setting it based on Accept request header or $this->defaultResponseContentType
        }

        $responseContentType = $this->createResponseBody($response->getHeaderLine("Content-Type"), $parameters);

        $responseBody = $this->streamFactory->createStream($responseContentType);

        return $response->withBody($responseBody);
    }

    protected function createResponseBody(string $responseContentType, object $parameters): string // Override this to handle custom response content types
    {
        if ($responseContentType === "text/html") {
            $responseBody = $this->streamFactory->createStream(
                $this->html($parameters)
            );
        } else {
            $responseBody = $this->streamFactory->createStream(
                $this->json($parameters)
            );
        }

        return $responseBody;
    }

    protected function html(object $parameters): string // Override this to customize html response format
    {
        return "<html lang='en'><head><title>Frostnova</title></head><body>" . var_export($parameters, true) . "</body></html>";
    }

    protected function json(object $parameters): string // Override this customize json response format
    {
        return json_encode($parameters);
    }
}
