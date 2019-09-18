erface();
        $code = $this->pass->apply(
            'public function __isset($name) {}',
            $this->mockedConfiguration
        );
        $this->assertTrue(\mb_strpos($code, ' : bool') !== false);
    }

    /**
     * @test
     */
    public function itShouldAddTypeHintsOnToStringMethod()
    {
        $this->configureForClass();
        $code = $this->pass->apply(
            'public function __toString() {}',
            $this->mockedConfiguration
        );
        $this->assertTrue(\mb_strpos($code, ' : string') !== false);

        $this->configureForInterface();
        $code = $this->pass->apply(
            'public function __toString() {}',
            $this->mockedConfiguration
        );
        $this->assertTrue(\mb_strpos($code, ' : string') !== false);
    }

    /**
     * @test
     */
    public function itShouldAddTypeHintsOnCallMethod()
    {
        $this->configureForClass();
        $code = $this->pass->apply(
            'public function __call($method, array $args) {}',
            $this->mockedConfiguration
        );
        $this->assertTrue(\mb_strpos($code, 'string $method') !== false);

        $this->configureForInterface();
        $code = $this->pass->apply(
            'public function __call($method, array $args) {}',
            $this->mockedConfiguration
        );
        $this->assertTrue(\mb_strpos($code, 'string $method') !== false);
    }

    /**
     * @test
     */
    public function itShouldAddTypeHintsOnCallStaticMethod()
    {
        $this->configureForClass();
        $code = $this->pass->apply(
            'public static function __callStatic($method, array $args) {}',
            $this->mockedConfiguration
        );
        $this->assertTru