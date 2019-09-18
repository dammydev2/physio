'
                class Psy_Test_CodeCleaner_ValidClassNamePass_ClassWithStatic {
                    public static function foo() {
                        return static::bar();
                    }
                }
            '],

            ['class A { static function b() { return new A; } }'],
            ['
                class A {
                    const B = 123;
                    function c() {
                        return A::B;
                    }
                }
            '],
            ['class A {} class B { function c() { return new A; } }'],

            // recursion
            ['class A { function a() { A::a(); } }'],

            // conditionally defined classes
            ['
                class A {}
                if (false) {
                    class A {}
                }
            '],
            ['
                class A {}
                if (true) {
                    class A {}
                } else if (false) {
                    class A {}
                } else {
                    class A {}
                }
            '],
            // ewww
            ['
                class A {}
                if (true):
                    class A 