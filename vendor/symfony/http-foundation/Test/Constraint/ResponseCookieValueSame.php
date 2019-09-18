 function protocolVersionProvider()
    {
        return [
            'untrusted without via' => ['HTTP/2.0', false, '', 'HTTP/2.0'],
            'untrusted with via' => ['HTTP/2.0', false, '1.0 fred, 1.1 nowhere.com (Apache/1.1)', 'HTTP/2.0'],
            'trusted without via' => ['HTTP/2.0', true, '', 'HTTP/2.0'],
            'trusted with via' => ['HTTP/2.0', true, '1.0 fred, 1.1 nowhere.com (Apache/1.1)', 'HTTP/1.0'],
            'trusted with via and protocol name' => ['HTTP/2.0', true, 'HTTP/1.0 fred, HTTP/1.1 nowhere.com (Apache/1.1)', 'HTTP/1.0'],
            'trusted with broken via' => ['HTTP/2.0', true, 'HTTP/1^0 foo', 'HTTP/2.0'],
            'trusted with partially-broken via' => ['HTTP/2.0', true, '1.0 fred, foo', 'HTTP/1.0'],
        ];
    }

    public function nonstandardRequestsData()
    {
        return [
            ['',  '', '/', 'http://host:8080/', ''],
            ['/', '', '/', 'http://host:8080/', ''],

            ['hello/app.php/x',  '', '/x', 'http://host:8080/hello/app.php/x', '/hello', '/hello/app.php'],
            ['/hello/app.php/x', '', '/x', 'http://host:8080/hello/app.php/x', '/hello', '/hello/app.php'],

            ['',      'a=b', '/', 'http://host:8080/?a=b'],
            ['?a=b',  'a=b', '/', 'http://host:8080/?a=b'],
            ['/?a=b', 'a=b', '/', 'http://host:8080/?a=b'],

            ['x',      'a=b', '/x', 'http://host:8080/x?a=b'],
            ['x?a=b',  'a=b', '/x', 'http://host:8080/x?a=b'],
            ['/x?a=b', 'a=b', '/x', 'http://host:8080/x?a=b'],

            ['hello/x',  '', '/x', 'http://host:8080/hello/x', '/hello'],
            ['/hello/x', '', '/x', 'http://host:8080/hello/x', '/hello'],

            ['hello/app.php/x',      'a=b', '/x', 'http://host:8080/hello/app.php/x?a=b', '/hello', '/hello/app.php'],
            ['hello/app.php/x?a=b',  'a=b', '/x', 'http://host:8080/hello/app.php/x?a=b', '/hello', '/hello/app.php'],
            ['/hello/app.php/x?a=b', 'a=b', '/x', 'http://host:8080/hello/app.php/x?a=b', '/hello', '/hello/app.php'],
        ];
    }

    /**
     * @dataProvider nonstandardRequestsData
     */
    public function testNonstanda