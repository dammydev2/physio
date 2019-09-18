<?php
namespace Hamcrest\Type;

class IsCallableTest extends \Hamcrest\AbstractMatcherTest
{

    public static function callableFunction()
    {
    }

    public function __invoke()
    {
    }

    protected function createMatcher()
    {
        return \Hamcrest\Type\IsCallable::callableValue();
    }

    public function testEvaluatesToTrueIfArgumentIsFunctionName()
    {
        assertThat('preg_match', callableValue());
    }

    public function testEvaluatesToTrueIfArgumentIsStaticMethodCallback()
    {
        assertThat(
            array('Hamcrest\Type\IsCallableTest', 'callableFunction'),
            callableValue()
        );
    }

    public function testEvaluatesToTrueIfArgumentIsInstanceMethodCallback()
    {
        assertThat(
            array($this, 'testEvaluatesToTrueIfArgumentIsInstanceMethodCallback'),
          