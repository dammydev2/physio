<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Translation\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Translator;

class DataCollectorTranslatorTest extends TestCase
{
    public function testCollectMessages()
    {
        $collector = $this->createCollector();
        $collector->setFallbackLocales(['fr', 'ru']);

        $