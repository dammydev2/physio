           '/foo%20bar',
                '/home',
            ],
            [
                '/foo%20bar/app.php/home',
                [
                    'SCRIPT_FILENAME' => '/home/John Doe/public_html/foo bar/app.php',
                    'SCRIPT_NAME' => '/foo bar/app.php',
                    'PHP_SELF' => '/foo bar/app.php',
                ],
                '/foo%20bar/app.php',
                '/home',
            ],
            [
                '/foo%20bar/app.php/home%3Dbaz',
                [
                    'SCRIPT_FILENAME' => '/home/John Doe/public_html/foo bar/app.php',
                    'SCRIPT_NAME' => '/foo bar/app.php',
                    'PHP_SELF' => '/foo bar/app.php',
                ],
                '/foo%20bar/app.php',
                '/home%3Dbaz',
            ],
            [
                '/foo/bar+baz',
                [
                    'SCRIPT_FILENAME' => '/home/John Doe/public_html/foo/app.php',
                    'SCRIPT_NAME' => '/foo/app.php',
                    'PHP_SELF' => '/foo/app.php',
                ],
                '/foo',
                '/bar+baz',
            ],
        ];
    }

    /**
     * @dataProvider urlencodedStringPrefixData
     */
    public function testUrlencodedStringPrefix($string, $prefix, $expect)
    {
        $request = new Request();

        $me = new \ReflectionMethod($request, 'getUrlencodedPrefix');
        $me->setAccessible(true);

        $this->assertSame($expect, $me->invoke($request, $string, $prefix));
    }

    public function urlencodedStringPrefixData()
    {
        return [
            ['foo', 'foo', 'foo'],
            ['fo%6f', 'foo', 'fo%6f'],
            ['foo/bar', 'foo', 'foo'],
            ['fo%6f/bar', 'foo', 'fo%6f'],
            ['f%6f%6f/bar', 'foo', 'f%6f%6f'],
            ['%66%6F%6F/bar', 'foo', '%66%6F%6F'],
            ['fo+o/bar', 'fo+o', 'fo+o'],
            ['fo%2Bo/bar', 'fo+o', 'fo%2Bo'],
        ];
    }

    private function disableHttpMethodParameterOverride()
    {
        $class = new \ReflectionClass('Symfony\\Component\\HttpFoundation\\Request');
        $property = $class->getProperty('httpMethodParameterOverride');
        $property->setAccessible(true);
        $property->setValue(false);
    }

    private function getRequestIn