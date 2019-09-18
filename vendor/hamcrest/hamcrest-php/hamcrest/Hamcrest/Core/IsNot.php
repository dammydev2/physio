<?php
namespace Hamcrest\Text;

class IsEmptyStringTest extends \Hamcrest\AbstractMatcherTest
{

    protected function createMatcher()
    {
        return \Hamcrest\Text\IsEmptyString::isEmptyOrNullString();
    }

    public function testEmptyDoesNotMatchNull()
    {
        $this->assertDoesNotMatch(emptyString(), null, 'null');
    }

    public function testEmptyDoesNotMatchZero()
    {
        $this->assertDoesNotMatch(emptyString(), 0, 'zero');
    }

    public function testEmptyDoesNotMatchFalse()
    {
        $this->assertDoesNotMatch(emptyString(), false, 'false');
    }

    public function testEmptyDoesNotMatchEmptyArray()
    {
        $this->assertDoesNotMatch(emptyString(), array(), 'empty array');
    }

    public function testEmptyMatchesEmptyString()
    {
        $this->assertMatches(emptyStr