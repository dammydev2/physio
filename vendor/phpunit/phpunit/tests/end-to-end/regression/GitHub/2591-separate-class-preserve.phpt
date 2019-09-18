        $this->logicalXor(
                $this->isTrue(),
                $this->isFalse()
            )
        );

        $this->expectException(AssertionFailedError::class);

        $this->assertThat(
            true,
            $this->logicalXor(
                $this->isTrue(),
                $this->isTrue()
            )
        );
    }

    public function testStringContainsStringCanBeAsserted(): void
    {
        $this->assertStringContainsString('bar', 'foobarbaz');

        try {
            $this->assertStringContainsString('barbara', 'foobarbaz');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    public function testString