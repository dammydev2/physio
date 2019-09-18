<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Debug\Tests\FatalErrorHandler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\Debug\FatalErrorHandler\UndefinedFunctionFatalErrorHandler;

class UndefinedFunctionFatalErrorHandlerTest extends TestCase
{
    /**
     * @dataProvider provideUndefinedFunctionData
     */
    public function testUndefinedFunction($error, $translatedMessage)
    {
        $handler = new UndefinedFunctionFatalErrorHand