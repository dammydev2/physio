    }

        $handle = fopen($this->tmpDir.'/index.csv', 'r');
        for ($i = 0; $i < $iteration; ++$i) {
            $row = fgetcsv($handle);
            $this->assertEquals('token'.$i, $row[0]);
            $this->assertEquals('127.0.0.'.$i, $row[1]);
            $this->assertEquals('http://foo.bar/'.$i, $row[3]);
        }
        $this->assertFalse(fgetcsv($handle));
    }

    public function testReadLineFromFile()
    {
        $r = new \ReflectionMethod($this->storage, 'readLineFromFile');

        $r->setAccessible(true);

        $h = tmpfile();

        fwrite($h, "line1\n\n\nline2\n");
        fseek($h, 0, SEEK_END);

        $this->assertEquals('line2', $r->invoke($this->storage, $h));
        $this->assertEquals('line1', $r->invoke($this->storage, $h));
    }

    protected function cleanDir()
    {
        $flags = \FilesystemIterator::SKIP_DOTS;
        $iterator = new \RecursiveDirectoryIterator($this->tmpDir, $flags);
        $iterator = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST