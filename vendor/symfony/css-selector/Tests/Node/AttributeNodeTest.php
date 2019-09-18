<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Debug;

use PHPUnit\Framework\MockObject\Matcher\StatelessInvocation;

/**
 * Autoloader checking if the class is really defined in the file found.
 *
 * The ClassLoader will wrap all registered autoloaders
 * and will throw an exception if a file is found but does
 * not declare the class.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Christophe Coevoet <stof@notk.org>
 * @author Nicolas Grekas <p@tchwork.com>
 * @author Guilhem Niot <guilhem.niot@gmail.com>
 */
class DebugClassLoader
{
    private $classLoader;
    private $isFinder;
    private $loaded = [];
    private static $caseCheck;
    private static $checkedClasses = [];
    private static $final = [];
    private static $finalMethods = [];
    private static $deprecated = [];
    private static $internal = [];
    private static $internalMethods = [];
    private static $annotatedParameters = [];
    private static $darwinCache = ['/' => ['/', []]];

    public function __construct(callable $classLoader)
    {
        $this->classLoader = $classLoader;
        $this->isFinder = \is_array($classLoader) && method_exists($classLoader[0], 'findFile');

        if (!