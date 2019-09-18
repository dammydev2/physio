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

use Psy\Reflection\ReflectionConstant_;

\define('Psy\\Test\\Reflection\\SOME_CONSTANT', 'yep');

class ReflectionConstantTest extends \PHPUnit\Framework\TestCase
{
    public function testConstruction()
    {
        $refl = new ReflectionConstant_('Psy\\Test\\Reflection\\SOME_CONSTANT');

        $this->assertFalse($refl->getDocComment());
        $this->assertEquals('Psy\\Test\\Reflection\\SOME_CONSTANT', $refl->getName());
        $this->assertEquals('Psy\\Test\\Reflection', $refl->getNamespaceName());
        $this->assertEquals('yep', $refl->getValue());
        $this->assertTrue($refl->inNamespace());
        $this->assertEquals('Psy\\Test\\Reflection\\SOME_CONSTANT', (string) $refl);
        $this->assertNull($refl->getFileName());
    }

    public function testBuiltInConstant()
    {
        $refl = new ReflectionConstant_('PHP_VERSION');

        $this->assertEquals('PHP_VERSION', $refl->getName());
        $this->assertEquals('PHP_VERSION', (string) $refl);
        $this->assertEquals(PHP_VERSION, $refl->getValue());
        $this->assertFalse($refl->inNamespace());
        $this->assertSame('', $refl->getNamespaceName());
    }

    /**
     * @dataProvider magicConstants
     */
    public function testIsMagicConstant($name, $is)
    {
        $this->assertEquals($is, ReflectionConstant_::isMa