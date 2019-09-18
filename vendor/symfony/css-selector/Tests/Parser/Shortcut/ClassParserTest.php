<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Debug\Exception;

use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * FlattenException wraps a PHP Error or Exception to be able to serialize it.
 *
 * Basically, this class removes all objects from the trace.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class FlattenException
{
    private $message;
    private $code;
    private $previous;
    private $trace;
    private $class;
    private $statusCode;
    private $headers;
    private $file;
    private $line;

    public static function create(\Exception $exception, $statusCode = null, array $headers = [])
    {
        return static::createFromThrowable($exception, $statusCode, $headers);
    }

    public static function createFromThrowable(\Throwable $exception, ?int $statusCode = null, array $headers = []): self
    {
        $e = new static();
        $e->setMessage($exception->getMessage());
        $e->setCode($exception->getCode());

        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
   