<?php

declare(strict_types=1);

namespace App\ErrorHandler\ErrorRenderer;

use Symfony\Component\ErrorHandler\ErrorRenderer\SerializerErrorRenderer;
use Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * This error renderer uses exception HTTP status code and custom message
 * instead of the default one.
 *
 * Class CustomizableSerializerErrorRenderer
 */
class CustomizableSerializerErrorRenderer extends SerializerErrorRenderer
{
    private $serializer;
    private $format;
    private $fallbackErrorRenderer;
    private $debug;

    public function __construct(
        SerializerInterface $serializer,
        $format,
        ?ErrorRendererInterface $fallbackErrorRenderer = null,
        $debug = false
    ) {
        if (!\is_string($format) && !\is_callable($format)) {
            throw new \TypeError(sprintf('Argument 2 passed to "%s()" must be a string or a callable, "%s" given.', __METHOD__, \gettype($format)));
        }

        if (!\is_bool($debug) && !\is_callable($debug)) {
            throw new \TypeError(sprintf('Argument 4 passed to "%s()" must be a boolean or a callable, "%s" given.', __METHOD__, \gettype($debug)));
        }

        $this->serializer = $serializer;
        $this->format = $format;
        $this->fallbackErrorRenderer = $fallbackErrorRenderer ?? new HtmlErrorRenderer();
        $this->debug = $debug;
    }

    /**
     * {@inheritdoc}
     */
    public function render(\Throwable $exception): FlattenException
    {
        $headers = ['Vary' => 'Accept'];
        $debug = \is_bool($this->debug) ? $this->debug : ($this->debug)($exception);
        if ($debug) {
            $headers['X-Debug-Exception'] = rawurlencode($exception->getMessage());
            $headers['X-Debug-Exception-File'] = rawurlencode($exception->getFile()).':'.$exception->getLine();
        }

        $flattenException = FlattenException::createFromThrowable($exception, null, $headers);

        try {
            $format = \is_string($this->format) ? $this->format : ($this->format)($flattenException);
            $headers['Content-Type'] = Request::getMimeTypes($format)[0] ?? $format;

            $flattenException->setAsString($this->serializer->serialize($flattenException, $format, [
                'exception' => $exception,
                'debug' => $debug,
                'title' => $exception->getMessage(),
                'status' => $this->getHttpStatusCode($exception)
            ]));
        } catch (NotEncodableValueException $e) {
            $flattenException = $this->fallbackErrorRenderer->render($exception);
        }

        return $flattenException->setHeaders($flattenException->getHeaders() + $headers);
    }

    /**
     * @param \Throwable $exception
     * @return int
     */
    private function getHttpStatusCode(\Throwable $exception): int
    {
        $statusCode = 0;

        if (method_exists($exception, 'getStatusCode') && $exception->getStatusCode()) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = $exception->getCode();
        }

        return $statusCode;
    }
}
