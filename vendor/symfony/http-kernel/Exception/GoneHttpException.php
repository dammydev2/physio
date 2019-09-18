cho 'foo';
        });

        $domResponse = $m->invoke($client, $response);
        $this->assertEquals('foo', $domResponse->getContent());
    }

    public function testUploadedFile()
    {
        $source = tempnam(sys_get_temp_dir(), 'source');
        file_put_contents($source, '1');
        $target = sys_get_temp_dir().'/sf.moved.file';
        @unlink($target);

        $kernel = new TestHttpKernel();
        $client = new HttpKernelBrowser($kernel);

        $files = [
            ['tmp_name' => $source, 'name' => 'original', 'type' => 'mime/original', 'size' => null, 'error' => UPLOAD_ERR_OK],
            new UploadedFile($source, 'original', 'mime/original', UPLOAD_ERR_OK, true),
        ];

        $file = null;
        foreach ($files as $file) {
            $client->request('POST', '/', [], ['foo' => $fi