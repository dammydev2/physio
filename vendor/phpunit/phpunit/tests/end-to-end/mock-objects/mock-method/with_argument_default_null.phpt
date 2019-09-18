<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Test;

use PHPUnit\Runner\AfterIncompleteTestHook;
use PHPUnit\Runner\AfterLastTestHook;
use PHPUnit\Runner\AfterRiskyTestHook;
use PHPUnit\Runner\AfterSkippedTestHook;
use PHPUnit\Runner\AfterSuccessfulTestHook;
use PHPUnit\Runner\AfterTestErrorHook;
use PHPUnit\Runner\AfterTestFailureHook;
use PHPUnit\Runner\AfterTestHook;
use PHPUnit\Runner\AfterTestWarningHook;
use PHPUnit\Runner\BeforeFirstTestHook;
use PHPUnit\Runner\BeforeTestHook;

final class Extension implements BeforeFirstTestHook, BeforeTestHook, AfterTestHook, AfterSuccessfulTestHook, AfterSkippedTestHook, AfterRiskyTestHook, AfterIncompleteTestHook, AfterTestErrorHook, AfterTestWarningHook, AfterTestFailureHook, AfterLastTestHook
{
    private $amountOfInjectedArguments = 0;

    public function __construct()
    {
        $this->amountOfInjectedArguments = \co