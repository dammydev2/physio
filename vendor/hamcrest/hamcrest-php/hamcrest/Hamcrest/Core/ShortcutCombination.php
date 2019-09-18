<?php
namespace Hamcrest\Text;

class StringContainsIgnoringCaseTest extends \Hamcrest\AbstractMatcherTest
{

    const EXCERPT = 'ExcErPt';

    private $_stringContains;

    public function setUp()
    {
        $this->_stringContains = \Hamcrest\Text\StringContainsIgnoringCase::containsStringIgnoringCase(
            strtolower(self::EXCERPT)
        );
    }

    protected function createMatcher()
    {
        return $this->_stringContains;
    }

    public function testEvaluatesToTrueIfArgumentContainsSpecifiedSubstring()
    {
        $this->assertTrue(
            $this->_stringContains->matches(self::EXCERPT . 'END'),
            'should be true if excerpt at beginning'
        );
        $this->assertTrue(
            $this->_stringContains->matches('START' . self::EXCERPT),
            'should be true if excerpt at end'
        );
        $this->assertTrue(
            $this->_stringContains->m