           .'|c/([^/]++)(*:549)'
                        .')'
                    .')'
                .')'
                .')/?$}sD',
        ];
        $this->dynamicRoutes = [
            47 => [[['_route' => 'foo', 'def' => 'test'], ['bar'], null, null, false, true, null]],
            70 => [[['_route' => 'bar'], ['foo'], ['GET' => 0, 'HEAD' => 1], null, false, true, null]],
            90 => [[['_route' => 'barhead'], ['foo'], ['GET' => 0], null, false, true, null]],
            115 => [
                [['_route' => 'baz4'], ['foo'], null, null, true, true, null],
                [['_route' => 'baz5'], ['foo'], ['POST' => 0], null, true, true, null],
                [['_route' => 'baz.baz6'], ['foo'], ['PUT' => 0], null, true, true, null],
            ],
            131 => [[['_route' => 'quoter'], ['quoter'], null, null, false, true, null]],
            160 => [[['_route' => 'foo1'], ['foo'], ['PUT' => 0], null, false, true, null]],
            168 => [[['_route' => 'bar1'], ['bar'], null, null, false, true, null]],
            181 => [[['_route' => 'overridden'], ['var'], null, null, false, true, null]],
            204 => [[['_route' => 'foo2'], ['foo1'], null, null, false, true, null]],
            212 => [[['_route' => 'bar2'], ['bar1'], null, null, false, true, null]],
            248 => [[['_route' => 'helloWorld', 'who' => 'World!'], ['who'], null, null, false, true, null]],
            279 => [[['_route' => 'foo3'], ['_locale', 'foo'], null, null, false, true, null]],
            287 => [[['_route' => 'bar3'], ['_locale', 'bar'], null, null, false, true, null]],
            309 => [[['_route' => 'foo4'], ['foo'], null, null, false, true, null]],
            371 => [[['_route' =