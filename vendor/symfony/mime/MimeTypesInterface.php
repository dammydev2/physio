<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Mime\Tests;

use Symfony\Component\Mime\Exception\RuntimeException;
use Symfony\Component\Mime\MimeTypeGuesserInterface;
use Symfony\Component\Mime\MimeTypes;

/**
 * @requires extension fileinfo
 */
class MimeTypesTest extends AbstractMimeTypeGuesserTest
{
    protected function getGuesser(): MimeTypeGuesserInterface
    {
        return new MimeTypes();
    }

    public function testUnsupportedGuesser()
    {
        $guesser = $this->getGuesser();
        $guesser->registerGuesser(new class() implements MimeTypeGuesserInterface {
            public function isGuesserSuppo