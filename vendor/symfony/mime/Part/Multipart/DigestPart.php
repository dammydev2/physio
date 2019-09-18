<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Mime\Tests\Part\Multipart;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\Mime\Part\TextPart;

class FormDataPartTest extends TestCase
{
    public function testConstructor()
    {
        $r = new \ReflectionProperty(TextPart::class, 'encoding');
        $r->setAccessible(true);

        $b = new TextPart('content');
        $c = DataPart::fromPa