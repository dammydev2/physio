<?php
namespace Hamcrest\Core;

class SetTest extends \Hamcrest\AbstractMatcherTest
{

    public static $_classProperty;
    public $_instanceProperty;

    protected function setUp()
    {
        self::$_classProperty = null;
        unset($this->_instanceProperty);
    }

    protected function createMatcher()
    {
        return \Hamcrest\Core\Set::set('property_name');
    }

    public function testEvaluatesToTrueIfArrayPropertyIsSet()
    {
        assertThat(array('foo' => 'bar'), set('foo'));
    }

    public function testNegatedEvaluatesToFalseIfArrayPropertyIsSet()
    {
        assertThat(array('foo' => 'bar'), not(notSet('foo')));
    }

    public function testEvaluatesToTrueIfClassPropertyIsSet()
    {
        self::$_classProperty = 'bar';
        assertThat('Hamcrest\Core\SetTest', set('_classProperty'));
    }

    public function testNegatedEvaluatesToFalseIfClassPropertyIsSet()
    {
        self::$_classProperty = 'bar';
        assertThat('Hamcrest\Core\SetTest', not(notSet('_classProperty')));
    }

    public function testEvaluatesToTrueIfObjectPropertyIsSet()
    {
        $this->_instanceProperty = 'bar';
        assertThat($this, set('_instanceProperty'));
    }

    public function testNegatedEvaluatesToFalseIfObjectPropertyIsSet()
    {
        $this->_instanceProperty = 'bar';
        assertThat($this, not(notSet('_instanceProperty')));
    }

    public function testEvaluatesToFalseIfArrayPropertyIsNotSet()
    {
        assertThat(array('foo' => 'bar'), not(set('baz')));
    }

    public function testNegatedEvaluatesToTrueIfArrayPropertyIsNotSet()
    {
        assertThat(array('foo' => 'bar'), notSet('baz'));
    }

    public function testEvaluatesToFalseIfClassPropertyIsNotSet()
    {
        assertThat('Hamcrest\Core\SetTest', not(set('_classProperty')));
    }

    public function testNegatedEvaluatesToTrueIfClassPropertyIsNotSet()
    {
        assertThat('Hamcrest\Core\SetTest', notSet('_classProperty'));
    }

    public function testEvaluatesToFalseIfObjectPropertyIsNotSet()
    {
        assertThat($this, not(set('_instanceProperty')));
    }

    public function testNega