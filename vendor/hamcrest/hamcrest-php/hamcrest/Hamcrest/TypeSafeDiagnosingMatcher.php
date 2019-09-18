<?php
namespace Hamcrest\Arrays;

use Hamcrest\AbstractMatcherTest;

class IsArrayContainingInOrderTest extends AbstractMatcherTest
{

    protected function createMatcher()
    {
        return IsArrayContainingInOrder::arrayContaining(array(1, 2));
    }

    public function testHasAReadableDescription()
    {
        $this->assertDescription('[<1>, <2>]', arrayContaining(array(1, 2)));
    }

    public function testMatchesItemsInOrder()
    {
        $this->assertMatches(arrayContaining(array(1, 2, 3)), array(1, 2, 3), 'in order');
        $this->assertMatches(arrayContaining(array(1)), array(1), 'single');
    }

    public function testAppliesMatchersInOrder()
    {
        $this->assertMatches(
            arrayContaining(array(1, 2, 3)),
            array(1, 2, 3),
            'in order'
        );
        $this->assertMa