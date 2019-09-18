$this->assertCount(1001, $res['context'][0]);
        $this->assertEquals('Over 1000 items (2000 total), aborting normalization', $res['context'][0]['...']);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testThrowsOnInvalidEncoding()
    {
        if (version_compare(PHP_VERSION, '5.5.0', '<')) {
            // Ignore the warning that will be emitted by PHP <5.5.0
            \PHPUnit_Framework_Error_Warning::$enabled = false;
        }
        $formatter = new NormalizerFormatter();
        $reflMethod = new \ReflectionMethod($formatter, 'toJson');
        $reflMethod->setAccessible(true);

        // send an invalid unicode sequence as a object that can't be cleaned
        $record = new \stdClass;
        $record->message = "\xB1\x31";
        $res = $reflMethod->invoke($formatter, $record);
        if (PHP_VERSION_ID < 50500 && $res === '{"message":null}') {
            throw new \RuntimeException('PHP 5.3/5.4 throw a warning and null the value instead of returning false entirely');
        }
    }

    public function testConvertsInvalidEncodingAsLatin9()
    {
        if (version_compare(PHP_VERSION, '5.5.0', '<')) {
            // Ignore the warning that will be emitted by PHP <5.5.0
            \PHPUnit_Framework_Error_Warning::$enabled = false;
        }
        $formatter = new NormalizerFormatter();
        $reflMethod = new \ReflectionMethod($formatter, 'toJson');
        $reflMethod->setAccessible(true);

        $res = $reflMethod->invoke($formatter, array('message' => "\xA4\xA6\xA8\xB4\xB8\xBC\xBD\xBE"));

        if (version_compare(PHP_VERSION, '5.5.0', '>=')) {
            $this->assertSame('{"message":"€ŠšŽžŒœŸ"}', $res);
        } else {
            // PHP <5.5 does not return false for an element encoding failure,
            // instead it emits a warning (possibly) and nulls the value.
            $this->assertSame('{"message":null}', $res);
        }
    }

    /**
     * @param mixed $in     Input
     * @param mixed $expect Expected output
     * @covers Monolog\Formatter\NormalizerFormatter::detectAndCleanUtf8
     * @dataProvider providesDetectAndCleanUtf8
     */
    public function testDetectAndCleanUtf8($in, $expect)
    {
        $formatter = new NormalizerFormatter();
        $formatter->detectAndCleanUtf8($in);
        $this->assertSame($expect, $in);
    }

    public function providesDetectAndCleanUtf8()
    {
        $obj = new \stdClass;

        return array(
            'null' => array(null, null),
            'int' => array(123, 123),
      