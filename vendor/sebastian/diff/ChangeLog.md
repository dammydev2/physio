ssertNotSame('', $diff);
        $this->assertValidUnifiedDiffFormat($diff);

        $diff = self::setDiffFileHeader($diff, $this->fileFrom);

        $this->assertNotFalse(\file_put_contents($this->fileFrom, $from));
        $this->assertNotFalse(\file_put_contents($this->filePatch, $diff));

        $command = \sprintf(
            'patch -u --verbose --posix %s < %s',
            \escapeshellarg($this->fileFrom),
            \escapeshellarg($this->filePatch)
        );

        $p = new Process($command);
        $p->run();

        $this->assertProcessSuccessful($p);

        $this->assertStringEqualsFile(
            $this->fileFrom,
            $to,
            \sprintf('Patch command "%s".', $command)
        );
    }

    private function assertProcessSuccessful(Process $p): void
    {
        $this->assertTrue(
            $p->isSuccessful(),
            \sprintf(
                "Command exec. was not successful:\n\"%s\"\nOutput:\n\"%s\"\nStdErr:\n\"%s\"\nExit code %d.\n",
                $p->getCommandLine(),
                $p->getOutput(),
                $p->getErrorOutput(),
                $p->getExitCode()
            )
        );
    }

    private function cleanUpTempFiles(): void
    {
        @\unlink($this->fileFrom . '.orig');
        @\unlink($this->fileFrom . '.rej');
        @\unlink($this->fileFrom);
        @\unlink($this->fileTo);
        @\unlink($this->filePatch);
    }

    private static function setDiffFileHeader(string $diff, string $file): string
    {
        $diffLines    = \preg_split('/(.*\R)/', $diff, -1, PREG_SPLIT_DELIM_CAPTUR