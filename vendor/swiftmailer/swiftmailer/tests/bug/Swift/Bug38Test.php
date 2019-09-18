<?php


class Swift_Mime_AttachmentTest extends Swift_Mime_AbstractMimeEntityTest
{
    public function testNestingLevelIsAttachment()
    {
        $attachment = $this->createAttachment($this->createHeaderSet(),
            $this->createEncoder(), $this->createCache()
            );
        $this->assertEquals(
            Swift_Mime_SimpleMimeEntity::LEVEL_MIXED, $attachment->getNestingLevel()
            );
    }

    public function testDispositionIsReturnedFromHeader()
    {
        /* -- RFC 2183, 2.1, 2.2.
     */

        $disposition = $this->createHeader('Content-Disposition', 'attachment');
        $attachment = $this->createAttachment($this->createHeaderSet([
            'Content-Disposition' => $disposition, ]),
            $this->createEncoder(), $this->createCache()
            );
        $this->assertEquals('attachment', $attachment->getDisposition());
    }

    public function testDispositionIsSetInHeader()
    {
        $disposition = $this->createHeader('Content-Disposition', 'attachment',
            [], false
            );
        $disposition->shouldReceive('setFieldBodyModel')
                    ->once()
                    ->with('inline');
        $disposition->shouldReceive('setFieldBodyModel')
                    ->zeroOrMoreTimes();

        $attachment = $this->createAttachment($this->createHeaderSet([
            'Content-Disposition' => $disposition, ]),
            $this->createEncoder(), $this->createCache()
            );
        $attachment->setDisposition('inline');
    }

    public function testDispositionIsAddedIfNonePresent()
    {
        $headers = $this->createHeaderSet([], false);
        $headers->shouldReceive('addParameterizedHeader')
                ->once()
                ->with('Content-Disposition', 'inline');
        $headers->shouldReceive('addParameterizedHeader')
                ->zeroOrMoreTimes();

        $attachment = $this->createAttachment($headers, $this->createEncoder(),
            $this->createCache()
            );
        $attachment->setDisposition('inline');
    }

    public function testDispositionIsAutoDefaultedToAttachment()
    {
        $headers = $this->createHeaderSet([], false);
        $headers->shouldReceive('addParameterizedHeader')
                ->once()
                ->with('Content-Disposition', 'attachment');
        $headers->shouldReceive('addParameterizedHeader')
                ->zeroOrMoreTimes();

        $attachment = $this->createAttachment($headers, $this->createEncoder(),
            $this->createCache()
            );
    }

    public function testDefaultContentTypeInitializedToOctetStream()
    {
        $cType = $this->createHeader('Content-Type', '',
            [], false
            );
        $cType->shouldReceive('setFieldBodyModel')
              ->once()
              ->with('application/octet-stream');
        $cType->shouldReceive('setFieldBodyModel')
              ->zeroOrMoreTimes();

        $attachment = $this->createAttachment($this->createHeaderSet([
            'Content-Type' => $cType, ]),
            $this->createEncoder(), $this->createCache()
            );
    }

    public function testFilenameIsReturnedFromHeader()
    {
        /* -- RFC 2183, 2.3.
     */

        $disposition = $this->createHeader('Content-Disposition', 'attachment',
            ['filename' => 'foo.txt']
            );
        $attachment = $this->createAttachment($this->createHeaderSet([
            'Content-Disposition' => $disposition, ]),
            $this->createEncoder(), $this->createCache()
            );
        $this->assertEquals('foo.txt', $attachment->getFilename());
    }

    public function testFilenameIsSetInHeader()
    {
        $disposition = $this->createHeader('Content-Disposition', 'attachment',
            ['filename' => 'foo.txt'], false
            );
        $disposition->shouldReceive('setParameter')
                    ->once()
                    ->with('filename', 'bar.txt');
        $disposition->shouldReceive('setParameter')
                    ->zeroOrMoreTimes();

        $attachment = $this->createAttachment($this->createHeaderSet([
            'Content-Disposition' => $disposition, ]),
            $this->createEncoder(), $this->createCache()
            );
        $attachment->setFilename('bar.txt');
    }

    public function testSettingFilenameSetsNameInContentType()
    {
        /*
     This is a legacy requirement which isn't covered by up-to-date RFCs.
     */

        $cType = $this->createHeader('Content-Type', 'text/plain',
            [], false
            );
        $cType->shouldReceive('setParameter')
              ->once()
              ->with('name', 'bar.txt');
        $cType->shouldReceive('setParameter')
              ->zeroOrMoreTimes();

        $attachment = $this->createAttachment($this->createHeaderSet([
            'Content-Type' => $cType, ]),
            $this->createEncoder(), $this->createCache()
            );
        $attachment->setFilename('bar.txt');
    }

    public function testSizeIsReturnedFromHeader()
    {
        /* -- RFC 2183, 2.7.
     */

        $disposition = $this->createHeader('Content-Disposition', 'attachment',
            ['size' => 1234]
            );
        $attachment = $this->createAttachment($this->createHeaderSet([
            'Content-Disposition' => $disposition, ]),
            $this->createEncoder(), $this->createCache()
            );
        $this->assertEquals(1234, $attachment->getSize());
    }

    public function testSizeIsSetInHeader()
    {
        $disposition = $this->createHeader('Content-Disposition', 'attachment',
            [], false
            );
        $disposition->shouldReceive('setParameter')
                    ->once()
                    ->with('size', 12345);
        $disposition->shouldReceive('setParameter')
                    ->zeroOrMoreTimes();

        $attachment = $this->createAttachment($this->createHeaderSet([
            'Content-Disposition' => $disposition, ]),
            $this->createEncoder(), $this->createCache()
            );
        $attachment->setSize(12345);
    }

    public function testFilnameCanBeReadFromFileStream()
    {
        $file = $this->createFileStream('/bar/file.ext', '');
        $disposition = $this->createHeader('Content-Disposition', 'attachment',
            ['filename' => 'foo.txt'], false
            );
        $disposition->shouldReceive('setParameter')
                    ->once()
                    ->with('filename', 'file.ext');

        $attachment = $this->createAttachment($this->createHeaderSet([
            'Content-Disposition' => $disposition, ]),
            $this->createEncoder()