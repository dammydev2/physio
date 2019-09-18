<?php
namespace Hamcrest\Core;

class AnyOfTest extends \Hamcrest\AbstractMatcherTest
{

    protected function createMatcher()
    {
        return \Hamcrest\Core\AnyOf::anyOf('irrelevant');
    }

    public function testAnyOfEvaluatesToTheLogicalDisjunctionOfTwoOtherMatchers()
    {
        assertThat('good', anyOf('bad', 'good'));
        assertThat('good', anyOf('good', 'good'));
        assertThat('good', anyOf('good', 'bad'));

        assertThat('good', not(anyOf('bad', startsWith('b'))));
    }

    public function testAnyOfEvaluatesToTheLogicalDisjunctionOfManyOtherMatchers()
    {
        assertThat('good', anyOf('bad', 'good', 'bad', 'bad', 'bad'));
        assertThat('good', not(anyOf('bad', 'bad', 'bad', 'bad', 'bad')));
    }

    public function testAnyOfSupportsMixedTypes()
    {
        $combined = anyOf(
            equalTo(new \Hamcrest\Core\SampleBaseClass('good')),
            equalTo(new \Hamcrest\Core\SampleBaseClass('ugly')),
            equalTo(new \Hamcrest\Core\SampleSubClass('good'))
        );

        assertThat(new \Hamcrest\Core\SampleSubClass('good'), $combined);
    }

    public function testAnyOfHasAReadableDescription()
    {
        $this->assertDescription(
            '("good" or "bad" or "ugly")',
            anyOf('good', 'bad', 'ugly')
        );
    }

    public function testNoneOfEvaluatesToTheLogicalDisjunctionOfTwoOtherMatchers()
    {
        assertThat('good', not(noneOf('bad', 'good')));
        assertThat('go