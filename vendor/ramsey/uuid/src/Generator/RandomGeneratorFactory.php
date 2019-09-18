setConstructorArgs([4, 8, 15])->getMock(),
                $this->getMockBuilder(SampleClass::class)->setMethods(null)->setConstructorArgs([16, 23, 42])->getMock(),
                $equalMessage
            ],
            [$object1, $object2, $equalMessage],
            [$book1, $book2, $equalMessage],
            [$book3, $book4, $typeMessage],
            [
                $this->getMockBuilder(Struct::class)->setMethods(null)->setConstructorArgs([2.3])->getMock(),
                $this->getMockBuilder(Struct::class)->setMethods(null)->setConstructorArgs([4.2])->getMock(),
                $equalMessage,
                0.5
            ]
        ];
    }

    /**
     * @dataProvider acceptsSucceedsProvider
     */
    public function testAcceptsSucceeds($expected, $actual): void
    {
        $this->assertTrue(
          $this->comparator->accepts($exp