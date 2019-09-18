
    }

    /**
     * @expectedException \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function testMoveLocalFileIsNotAllowed()
    {
        $file = new UploadedFile(
            __DIR__.'/Fixtures/test.gif',
            'original.gif',
            'image/gif',
            UPLOAD_ERR_OK
        );

        $movedFile = $file->move(__DIR__.'/Fixtures/directory');
    }

    public function failedUploadedFile()
    {
        foreach ([UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE, UPLOAD_ERR_PARTIAL, UPLOAD_ERR_NO_FILE, UPLOAD_ERR_CANT_WRITE, UPLOAD_ERR_NO_TMP_DIR, UPLOAD_ERR_EXTENSION, -1] as $error) {
            yield [new UploadedFile(
                __DIR__.'/Fixtures/test.gif',
                'original.gif',
                'image/gif',
                $error
            )];
        }
    }

    /**
     * @dataProvider failedUploadedFile
     */
    public function testMoveFailed(UploadedFile $file)
    {
        switch ($file->getError()) {
            case UPLOAD_ERR_INI_SIZE:
                $exceptionClass = IniSizeFileException::class;
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $exceptionClass = FormSizeFileException::class;
                break;
            case UPLOAD_ERR_PARTIAL:
                $exceptionClass = PartialFileException::class;
                break;
            case UPLOAD_ERR_NO_FILE:
                $exceptionClass = NoFileException::class;
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $exceptionClass = CannotWriteFileException::class;
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $exceptionClass = NoTmpDirFileException::class;
                break;
            case UPLOAD_ERR_EXTENSION:
                $exceptionClass = ExtensionFileException::class;
                break;
            default:
                $exceptionClass = FileException::class;
        }

        $this->expectException($exceptionClass);

        $file->move(__DIR__.'/Fixtures/directory');
    }

    public function testMoveLocalFileIsAllowedInTestMode()
    {
        $path = __DIR__.'/Fixtures/test.copy.gif';
        $targetDir = __DIR__.'/Fixtures/directory';
        $targetPath = $targetDir.'/test.copy.gif';
        @unlink($path);
        @unlink($targetPath);
        copy(__DIR__.'/Fixtures/test.gif', $path);

        $file = new UploadedFile(
            $path,
            'original.gif',
            'image/gif',
            UPLOAD_ERR_OK,
            true
        );

        $movedFile = $file->move(__DIR__.'/Fixtures/directory');

        $this->assertFileExists($targetPath);
        $this->assertFileNotExists($path);
        $this->assertEquals(realpath($targetPath), $movedFile->getRealPath());

        @unlink($targetPath);
    }

    public function testGetClientOriginalNameSanitizeFilename()
    {
        $file = new UploadedFile(
            __DIR__.'/Fixtures/test.gif',
            '../../original.gif',
            'image/gif'
        );

        $this->assertEquals('original.gif', $file->getClientOriginalName());
    }

    public function testGetSize()
    {
        $file = new UploadedFile(
            __DIR__.'/Fixtures/test.gif',
            'original.gif',
            'image/gif'
        );

        $this->assertEquals(filesize(__DIR__.'/Fixtures/test.gif'), $file->getSize());

        $file = new UploadedFile(
            __DIR__.'/Fixtures/test',
            'original.gif',
            'image/gif'
        );

        $this->assertEquals(filesize(__DIR__.'/Fixtures/test'), $file->getSize());
    }