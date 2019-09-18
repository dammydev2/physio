<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Test\Reflection;

use Psy\Reflection\ReflectionLanguageConstruct;
use Psy\Reflection\ReflectionLanguageConstructParameter;

class ReflectionLanguageConstructParameterTest extends \PHPUnit\Framework\TestCase
{
    public function testOptions()
    {
        $keyword = new ReflectionLanguageConstruct('die');

        $refl = new ReflectionLanguageConstructParameter($keyword, 'one', [
            'isArray'             => false,
            'defaultValue'        => null,
            'isOptional'          => false,
            'isPassedByReference' => false,
        ]);

        $this->assertNull($refl->getClass());
        $this->assertEquals('one', $refl->getName());
        $this->assertFalse($refl->isArray());
        $this->assertTrue($refl->isDefaultValueAvailable());
        $this->assertNull($refl->getDefaultValue());
        $this->assertFalse($refl->isOptional());
        $this->assertFalse($refl->isPassedByR