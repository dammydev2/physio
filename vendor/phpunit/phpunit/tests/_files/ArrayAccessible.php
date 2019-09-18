<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use PHPUnit\Framework\TestCase;

class RequirementsTest extends TestCase
{
    public function testOne(): void
    {
    }

    /**
     * @requires PHPUnit 1.0
     */
    public function testTwo(): void
    {
    }

    /**
     * @requires PHP 2.0
     */
    public function testThree(): void
    {
    }

    /**
     * @requires PHPUnit 2.0
     * @requires PHP 1.0
     */
    public function testFour(): void
    {
    }

    /**
     * @requires PHP 5.4.0RC6
     */
    public function testFive(): void
    {
    }

    /**
     * @requires PHP 5.4.0-alpha1
     */
    public function testSix(): void
    {
    }

    /**
     * @requires PHP 5.4.0beta2
     */
    public function testSeven(): void
    {
    }

    /**
     * @requires PHP 5.4-dev
     */
    public func