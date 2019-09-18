<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Util\TestDox;

use Exception;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Warning;

final class CliTestDoxPrinterTest extends TestCase
{
    /**
     * @var TestableCliTestDoxPrinter
     */
    private $printer;

    /**
     * @var TestableCliTestDoxPrinter
     */
    private $verbosePrinter;

    protected function setUp(): void
    {
        $this->printer        = new TestableCliTestDoxPrinter;
        $this->verbosePrinter = new TestableCliTestDoxPrinter(null, true);
    }

    protected function tearDown(): void
    {
        $this->printer        = null;
        $this->verbosePrinter = null;
    }

    public function testPrintsTheClassNameOfTheTestClass(): void
    {
        $this->printer->startTest($this);
        $this->printer->endTest($this, 0);

        $this->assertStringStartsWith('PHPUnit\Util\TestDox\CliTestDoxPrinter', $this->printer->getBuffer());
    }

    public function testPrintsThePr