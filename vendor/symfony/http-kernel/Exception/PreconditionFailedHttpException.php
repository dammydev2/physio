}
}
EOF;

        $output = Kernel::stripComments($source);

        // Heredocs are preserved, making the output mixing Unix and Windows line
        // endings, switching to "\n" everywhere on Windows to avoid failure.
        if ('\\' === \DIRECTORY_SEPARATOR) {
            $expected = str_replace("\r\n", "\n", $expected);
            $output = str_replace("\r\n", "\n", $output);
        }

        $this->assertEquals($expected, $output);
    }

    /**
     * @group legacy
     */
    public function testGetRootDir()
    {
        $kernel = new KernelForTest('test', true);

        $this->assertEquals(__DIR__.\DIRECTORY_SEPARATOR.'Fixtures', realpath($kernel->getRootDir()));
    }

    /**
     * @group legacy
     */
    public function testGetName()
    {
        $kernel = new KernelForTest('test', true);

        $this->assert