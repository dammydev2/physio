<?php
namespace Hamcrest\Arrays;

use Hamcrest\AbstractMatcherTest;

class IsArrayTest extends AbstractMatcherTest
{

    protected function createMatcher()
    {
        return IsArray::anArray(array(equalTo('irrelevant')));
    }

    public function testMatchesAnArrayThatMatchesAllTheElementMatchers()
    {
        $this->assertMatches(
            anArray(array(equalTo('a'), equalTo('b'), equalTo('c'))),
            array('a', 'b', 'c'),
            'should match array with matching elements'
        );
    }

    public function testDoesNotMatchAnArrayWhenElementsDoNotMatch()
    {
        $this->assertDoesNotMatch(
            anArray(array(equalTo('a'), equalTo('b'))),
            array('b', 'c'),
            'should not match array with different elements'
        );
    }

    public function testDoesNotMatchAnArrayOfDifferentSize()
    {
        $this->assertDoesNotMatch(
            anArray(array(equalTo('a'), equalTo('b'))),
            array('a', 'b', 'c'),
            'should not match larger array'
        );
        $this->assertDoesNotMatch(
            anArray(array(equalTo('a'), equalTo('b'))),
            array('a'),
            'should not match smaller array'
        );
    }

    public function testDoesNotMatchNull()
    {
        $this->assertDoesNotMatch(
            anArray(array(equalTo('a'))),
            null,
            'should not match null'
        );
    }

    public function testHasAReadableDescription()
    {
        $this->assertDescr