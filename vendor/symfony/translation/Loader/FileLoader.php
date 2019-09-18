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
use Symfony\Component\Translation\MessageSelector;

/**
 * @group legacy
 */
class MessageSelectorTest extends TestCase
{
    /**
     * @dataProvider getChooseTests
     */
    public function testChoose($expected, $id, $number)
    {
        $selector = new MessageSelector();

        $this->assertEquals($expected, $selector->choose($id, $number, 'en'));
    }

    public function testReturnMessageIfExactlyOneStandardRuleIsGiven()
    {
        $selector = new MessageSelector();

        $this->assertEquals('There are two apples', $selector->choose('There are two apples', 2, 'en'));
    }

    /**
     * @dataProvider getNonMatchingMessages
     * @expectedException \Symfony\Component\Translation\Exception\InvalidArgumentException
     */
    public function testThrowExceptionIfMatchingMessageCannotBeFound($id, $number)
    {
        $selector = new MessageSelector();

        $selector->choose($id, $number, 'en');
    }

    public function getNonMatchingMessages()
    {
        return [
            ['{0} There are no apples|{1} There is one apple', 2],
            ['{1} There is one apple|]1,Inf] There are %count% apples', 0],
            ['{1} There is one apple|]2,Inf] There are %count% apples', 2],
            ['{0} There are no apples|There is one apple', 2],
        ];
    }

    public function getChooseTests()
    {
        return [
            ['There are no apples', '{0} There are no apples|{1} There is one apple|]1,Inf] There are %count% apple