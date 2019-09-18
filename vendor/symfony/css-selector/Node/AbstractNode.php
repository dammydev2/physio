<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\CssSelector\Tests\Parser\Handler;

use Symfony\Component\CssSelector\Parser\Handler\NumberHandler;
use Symfony\Component\CssSelector\Parser\Token;
use Symfony\Component\CssSelector\Parser\Tokenizer\TokenizerPatterns;

class NumberHandlerTest extends AbstractHandlerTest
{
    public function getHandleValueTestData()
    {
        return [
            ['12', new Token(Token::TYPE_NUMBER, '12', 0), ''],
            ['12.34', new Token(Token::TYPE_NUMBER, '12.34', 0), ''],
            ['+12.34', new Token(Token::TYPE_NUMBER, '+12.34', 0), ''],
            ['-12.34', new Token(Token::TYPE_NUMBER, '-12.34', 0), ''],

            ['12 arg', new Token(Token::TYPE_NUMBER, '12',