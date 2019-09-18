<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Tests\Input;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputOption;

class InputOptionTest extends TestCase
{
    public function testConstructor()
    {
        $option = new InputOption('foo');
        $this->assertEquals('foo', $option->getName(), '__construct() takes a name as its first argument');
        $option = new InputOption('--foo');
        $this->assertEquals('foo', $option->getName(), '__construct() removes the leading -- of the option name');
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Impossible to have an option mode VALUE_IS_ARRAY if the option does no