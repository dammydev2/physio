<?php
namespace Hamcrest\Core;

class PhpForm
{
    public function __toString()
    {
        return 'php';
    }
}

class JavaForm
{
    public function toString()
    {
        return 'java';
    }
}

class BothForms
{
    public function __toString()
    {
        return 'php';
    }

    public function toString()
    {
        return 'java';
    }
}

class HasToStringTest extends \Hamcrest\AbstractMatcherTest
{

    protected function createMatcher()
    {
        return \Hamcrest\Core\HasToString::hasToString('foo');
    }

    public function testMatchesWhenToStringMatches()
    {
        $this->assertMatches(
            hasToString(equalTo('php')),
            new \Hamcrest\Core\PhpForm(),
            'correct __toString'
        );
        $this->assertMatches(
            hasToString(equalTo('java')),
            new \Hamcrest\Core\JavaForm(),
            'correct toString'
        );
    }

    public function testPicksJavaOverPhpToString()
    {
        $this->assertMatches(
            hasToString(equalTo('java')),
            new \Hamcrest\Core\BothForms(),
            'correct toString'
        );
    }

    public function testDoesNotMatchWhenToStringDoesNotMatch()
    {
        $this->assertDoesNotMatch(
            hasToString(equalTo('mismatch')),
            new \Hamcrest\Core\PhpForm(),
            'incorrect __toString'
        );
        $this->assertDoesNotMatch(
            hasToString(equalTo('mismatc