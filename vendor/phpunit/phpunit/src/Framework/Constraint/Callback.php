<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Framework\MockObject;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\Matcher\AnyInvokedCount;
use PHPUnit\Framework\MockObject\Matcher\AnyParameters;
use PHPUnit\Framework\MockObject\Matcher\Invocation as MatcherInvocation;
use PHPUnit\Framework\MockObject\Matcher\InvokedCount;
use PHPUnit\Framework\MockObject\Matcher\MethodName;
use PHPUnit\Framework\MockObject\Matcher\Parameters;
use PHPUnit\Framework\TestFailure;

/**
 * Main matcher which defines a full expectation using method, parameter and
 * invocation matchers.
 * This matcher encapsulates all the other matchers and allows the builder to
 * set the specific matchers when the appropriate methods are called (once(),
 * where() etc.).
 *
 * All properties are public so that they can easily be accessed by t