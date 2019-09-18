ke($client, $response);
        $this->assertEquals('foo', $domResponse->getContent());
    }

    public function testUploadedFile()
    {
        $source = tempnam(sys_get_temp_dir(), 'source');
        file_put_contents($source, '1');
        $target = sys_get_temp_dir().'/sf.moved.file';
        @unlink($target);

        $kernel = new TestHttpKernel();
        $client = new Client($kernel);

        $files = [
            ['tmp_name' => $source, 'name' => 'original', 'type' => 'mime/original', 'size' => null, 'error' => UPLOAD_ERR_OK],
            new UploadedFile($source, 'original', 'mime/original', UPLOAD_ERR_OK, true),
        ];

        $file = null;
        foreach ($files as $file) {
            $client->request('POST', '/', [], ['foo' => $file]);

            $files = $client->getRequest()->files->all();

            $this->assertCount(1, $files);

            $file = $files['foo'];

            $this->assertEquals('original', $file->getClientOriginalName());
            $this->assertEquals('mime/original', $file->getClientMimeType());
            $this->assertEquals(1, $file->getSize());
        }

        $file->move(\dirname($target), basename($target));

        $this->assertFileExists($target);
        unlink($target);
    }

    public function testUploadedFileWhenNoFileSelected()
    {
        $kernel = new TestHttpKernel();
        $client = new Client($kernel);

        $file = ['tmp_name' => '', 'name' => '', 'type' => '', 'size' => 0, 'error' => UPLOAD_ERR_NO_FILE];

        $client->request('POST', '/', [], ['foo' => $file]);

        $files = $client->getRequest()->files->all();

        $this->assertCount(1, $files);
        $this->assertNull($files['foo']);
    }

    public function testUploadedFileWhenSizeExceedsUploadMaxFileSize()
    {
        $source = tempnam(sys_get_temp_dir(), 'source');

        $kernel = new TestHttpKernel();
        $client = new Client($kernel);

        $file = $this
            ->getMockBuilder('Symfony\Component\HttpFoundation\File\UploadedFile')
            ->setConstructorArgs([$source, 'original', 'mime/original', UPLOAD_ERR_OK, true])
            ->setMeth