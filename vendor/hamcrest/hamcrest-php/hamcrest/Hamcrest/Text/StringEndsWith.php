<?php
namespace Hamcrest\Type;

class IsNumericTest extends \Hamcrest\AbstractMatcherTest
{

    protected function createMatcher()
    {
        return \Hamcrest\Type\IsNumeric::numericValue();
    }

    public function testEvaluatesToTrueIfArgumentMatchesType()
    {
        assertThat(5, numericValue());
        assertThat(0, numericValue());
        assertThat(-5, numericValue());
        assertThat(5.3, numericValue());
        assertThat(0.53, numericValue());
        assertThat(-5.3, numericValue());
        assertThat('5', numericValue());
        assertThat('0', numericValue());
        assertThat('-5', numericValue());
        assertThat('5.3', numericValue());
        assertThat('5e+3', numericValue());
        assertThat('0.053e-2',