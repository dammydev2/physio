);
    }

    public function testAssertNotIsWritable(): void
    {
        $this->assertNotIsWritable(__DIR__ . \DIRECTORY_SEPARATOR . 'NotExisting');

        $this->expectException(AssertionFailedError::class);

        $this->assertNotIsWritable(__FILE__);
    }

    public function testAssertDirectoryExists(): void
    {
        $this->assertDirectoryExists(__DIR__);

        $this->expectException(AssertionFailedError::class);

        $this->assertDirectoryExists(__DIR__ . \DIRECTORY_SEPARATOR . 'NotExisting');
    }

    public function testAssertDirectoryNotExists(): void
    {
        $this->assertDirectoryNotExists(__DIR__ . \DIRECTORY_SEPARATOR . 'NotExisting');

        $this->expectException(AssertionFailedError::class);

        $this->assertDirectoryNotExists(__DIR__);
    }

    public function testAssertDirectoryIsReadable(): void
    {
        $this->assertDirectoryIsReadable(__DIR__);

        $this->expectException(AssertionFailedError::class);

        $this->assertDirectoryIsReadable(__DIR__ . \DIRECTORY_SEPARATOR . 'NotExisting');
    }

    public funct