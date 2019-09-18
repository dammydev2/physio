   $actual  = \json_encode(['Mascott' => 'Beastie']);
        $message = '';

        $this->assertJsonStringNotEqualsJsonFile($file, $actual, $message);
    }

    public function testAssertJsonFileNotEqualsJsonFile(): void
    {
        $fileExpected = TEST_FILES_PATH . 'JsonData/simpleObject.json';
        $fileActual   = TEST_FILES_PATH . 'JsonData/arrayObject.json';
        $message      = '';

        $this->assertJsonFileNotEqualsJsonFile($fileExpected, $fileActual, $message);
    }

    public function testAssertJsonFileEqualsJsonFile(): void
    {
        $file    = TEST_FILES_PATH . 'JsonData/simpleObject.json';
        $message = '';

        $this->assertJsonFileEqualsJsonFile($file, $file, $message);
    }

    public function testAssertInsta