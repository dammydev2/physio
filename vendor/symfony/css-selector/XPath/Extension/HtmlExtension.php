<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Debug\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\GoneHttpException;
use Symfony\Component\HttpKernel\Exception\LengthRequiredHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionRequiredHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class FlattenExceptionTest extends TestCase
{
    public function testStatusCode()
    {
        $flattened = FlattenException::create(new \RuntimeException(), 403);
        $this->assertEquals('403', $flattened->getStatusCode());

        $flattened = FlattenException::create(new \RuntimeException());
        $this->assertEquals('500', $flattened->getStatusCode());

        $flattened = FlattenException::createFromThrowable(new \DivisionByZeroError(), 403);
        $this->assertEquals('403', $flattened->getStatusCode());

        $flattened = FlattenException::createFromThrowable(new \DivisionByZeroError());
        $this->assertEquals('500', $flattened->getStatusCode());

        $flattened = FlattenException::create(new NotFoundHttpException());
        $this->assertEquals('404', $flattened->getStatusCode());

        $flattened = FlattenException::create(new UnauthorizedHttpException('Basic realm="My Realm"'));
        $this->assertEquals('401', $flattened->getStatusCode());

        $flattened = FlattenException::create(new BadRequestHttpException());
        $this->assertEquals('400', $flattened->getStatusCode());

        $flattened = FlattenException::create(new NotAcceptableHttpException());
        $this->assertEquals('406', $flattened->getStatusCode());

        $flattened = FlattenException::create(new ConflictHttpException());
        $this->assertEquals('409', $flattened->getStatusCode());

        $flattened = FlattenException::create(new MethodNotAllowedHttpException(['POST']));
        $this->assertEquals('405', $flattened->getStatusCode());

        $flattened = FlattenException::create(new AccessDeniedHttpException());
        $this->assertEquals('403', $flattened->getStatusCode());

        $flattened = FlattenException::create(new GoneHttpException());
        $this->assertEquals('410', $flattened->getStatusCode());

        $flattened = FlattenException::create(new LengthRequiredHttpException());
        $this->assertEquals('411', $flattened->getStatusCode());

        $flattened = FlattenException::create(new PreconditionFailedHttpException());
        $this->assertEquals('412', $flattened->getStatusCode());

        $flattened = FlattenException::create(new PreconditionRequiredHttpException());
        $this->assertEquals('428', $flattened->getStatusCode());

        $flattened = FlattenException::create(new ServiceUnavailableHttpException());
        $this->assertEquals('503', $flattened->getStatusCode());

        $flattened = FlattenException::create(new TooManyRequestsHttpException());
        $this->assertEquals('429', $flattened->getStatusCode());

        $flattened = FlattenException::create(new UnsupportedMediaTypeHttpException());
        $this->assertEquals('415', $flattened->getStatusCode());

        if (class_exists(SuspiciousOperationException::class)) {
            $flattened = FlattenException::create(new SuspiciousOperationException());
            $this->assertEquals('400', $flattened->getStatusCode());
        }
    }

    public function testHeadersForHttpException()
    {
        $flattened = FlattenException::create(new MethodNotAllowedHttpException(['POST']));
        $this->assertEquals(['Allow' => 'POST'], $flattened->getHeaders());

        $flattened = FlattenException::create(new UnauthorizedHttpException('Basic realm="My Realm"'));
        $this->assertEquals(['WWW-Authenticate' => 'Basic realm="My Realm"'], $flattened->getHeaders());

        $flattened = FlattenException::create(new ServiceUnavailableHttpException('Fri, 31 Dec 1999 23:59:59 GMT'));
        $this->assertEquals(['Retry-After' => 'Fri, 31 Dec 1999 23:59:59 GMT'], $flattened->getHeaders());

        $flattened = FlattenException::create(new ServiceUnavailableHttpException(120));
        $this->assertEquals(['Retry-After' => 120], $flattened->getHeaders());

        $flattened = FlattenException::create(new TooManyRequestsHttpException('Fri, 31 Dec 1999 23:59:59 GMT'));
        $this->assertEquals(['Retry-After' => 'Fri, 31 Dec 1999 23:59:59 GMT'], $flattened->getHeaders());

        $flattened = FlattenException::create(new TooManyRequestsHttpException(120));
        $this->assertEquals(['Retry-After' => 120], $flattened->getHeaders());
    }

    /**
     * @dataProvider flattenDataProvider
     */
    public function testFlattenHttpException(\Throwable $exception)
    {
        $flattened = FlattenException::createFromThrowable($exception);
        $flattened2 = FlattenException::createFromThrowable($exception);

        $flattened->setPrevious($flattened2);

        $this->assertEquals($exception->getMessage(), $flattened->getM