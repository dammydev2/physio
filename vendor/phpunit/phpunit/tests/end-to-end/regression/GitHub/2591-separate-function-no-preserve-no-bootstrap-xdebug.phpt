      // different types
            [new \SampleClass(4, 8, 15), false],
            [false, new \SampleClass(4, 8, 15)],
            [[0        => 1, 1 => 2], false],
            [false, [0 => 1, 1 => 2]],
            [[], new \stdClass],
            [new \stdClass, []],
            // PHP: 0 == 'Foobar' => true!
            // We want these values to differ
            [0, 'Foobar'],
            ['Foobar', 0],
            [3, \acos(8)],
            [\acos(8), 3],
        ];
    }

    protected function equalValues(): array
    {
        // cyclic dependencies
        $book1                  = new \Book;
        $book1->author          = new \Author('Terry Pratchett');
        $book1->author->books[] = $book1;
        $book2                  = new \Book;
        $book2->author          = new \Author('Terry Pratchett');
        $book2->author->books[] = $book2;

        $object1  = new \SampleClass(4, 8, 15);
        $object2  = new \SampleClass(4, 8, 15);
        $storage1 = new \SplObjectStorage;
        $storage1->attach($object1);
        $storage2 = new \SplObjectStorage;
        $storage2->attach($object1);

        return [
            // strings
            ['a', 'A', 0, false, true], // ignore case
            // arrays
            [['a' => 1, 'b' => 2], ['b'