sertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    public function testNotArrayTypeCanBeAsserted(): void
    {
        $this->assertIsNotArray(null);

        try {
            $this->assertIsNotArray([]);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    public function testNotBoolTypeCanBeAsserted(): void
    {
        $this->assertIsNotBool(null);

        try {
            $this->assertIsNotBool(true);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    public function testNotFloatTypeCanBeAsserted(): void
    {
        $this->assertIsNotFloat(null);

        try {
            $this->assertIsNotFloat(0.0);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    public function testNotIntTypeCanBeAsserted(): void
    {
        $this->assertIsNotInt(null);

        try {
            $this->assertIsNotInt(1);
        } catch (Assertion