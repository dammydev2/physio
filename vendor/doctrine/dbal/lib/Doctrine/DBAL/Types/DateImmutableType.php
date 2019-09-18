<?php

namespace Doctrine\Tests\Common\Lexer;

class AbstractLexerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConcreteLexer
     */
    private $concreteLexer;

    public function setUp()
    {
        $this->concreteLexer = new ConcreteLexer();
    }

    public function dataProvider()
    {
        return array(
            array(
                'price=10',
                array(
                    array(
                        'value' => 'price',
                        'type' => 'string',
                        'position' => 0,
                    ),
                    array(
                        'value' => '=',
                        'type' => 'operator',
                        'position' => 5,
                    ),
                    array(
                        'value' => 10,
                        'type' => 'int',
                        'position' => 6,
                    ),
                ),
            ),
        );
    }

    public function testResetPeek()
    {
        $expectedTokens = array(
            array(
                'value' => 'price',
                'type' => 'string',
                'position' => 0,
            ),
            array(
                'value' => '=',
                'type' => 'operator',
                'position' => 5,
            ),
            array(
                'value' => 10,
                'type' => 'int',
                'position' => 6,
            ),
        );

        $this->concreteLexer->setInput('price=10');

        $this-