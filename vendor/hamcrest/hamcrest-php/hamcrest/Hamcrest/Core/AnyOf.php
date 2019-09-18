<?php
namespace Hamcrest\Core;

class IsCollectionContainingTest extends \Hamcrest\AbstractMatcherTest
{

    protected function createMatcher()
    {
        return \Hamcrest\Core\IsCollectionContaining::hasItem(equalTo('irrelevant'));
    }

    public function testMatchesACollectionThatContainsAnElementMatchingTheGivenMatcher()
    {
        $itemMatcher = hasItem(equalTo('a'));

        $this->assertMatches(
            $itemMatcher,
            array('a', 'b', 'c'),
            "should match list that contains 'a'"
        );
    }

    public function testDoesNotMatchCollectionThatDoesntContainAnElementMatchingTheGivenMatcher()
    {
        $matcher1 = hasItem(equalTo('a'));
        $this->assertDoesNotMatch(
            $matcher1,
            array('b', 'c'),
            "should not match list that doesn't contain 'a'"
        );

        $matcher2 = hasItem(equalTo('a'));
        $this->assertDoesNotMatch(
            $matcher2,
            array(),
            'should not match the empty list'
        );
    }

    public function testDoesNotMatchNull()
    {
        $this->assertDoesNotMatch(
            hasItem(equalTo('a')),
            null,
            'should not match null'
        );
    }

    public function testHasAReadab