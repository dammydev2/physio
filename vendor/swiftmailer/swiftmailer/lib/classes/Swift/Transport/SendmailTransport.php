            'Content-ID: <'.$cid.'>'."\r\n".
            'Content-Disposition: inline; filename=myimage.jpg'."\r\n".
            "\r\n".
            preg_quote(base64_encode('<image data>'), '~').
            "\r\n\r\n".
            '--\\1--'."\r\n".
            "\r\n\r\n".
            '--'.$boundary."\r\n".
            'Content-Type: application/pdf; name=foo.pdf'."\r\n".
            'Content-Transfer-Encoding: base64'."\r\n".
            'Content-Disposition: attachment; filename=foo.pdf'."\r\n".
            "\r\n".
            preg_quote(base64_encode('<pdf data>'), '~').
            "\r\n\r\n".
            '--'.$boundary.'--'."\r\n".
            '$~D',
            $message->toString()
            );
    }

    public function testAttachingAndDetachingContent()
    {
        $message = $this->createMessage();
        $message->setReturnPath('chris@w3style.co.uk');
        $message->setSubject('just a test subject');
        $message->setFrom([
            'chris.corbyn@swiftmailer.org' => 'Chris Corbyn', ]);

        $id = $message->getId();
        $date = preg_quote($message->getDate()->format('r'), '~');
        $boundary = $message->getBoundary();

        $part = $this->createMimePart();
        $part->setContentType('text/plain');
        $part->setCharset('iso-8859-1');
        $part->setBody('foo');

        $message->attach($part);

        $attachment = $this->createAttachment();
        $attachment->setContentType('application/pdf');
        $attachment->setFilename('foo.pdf');
        $attachment->setBody('<pdf data>');

        $message->attach($attachment);

        $file = $this->createEmbeddedFile();
        $file->setContentType('image/jpeg');
        $file->setFilename('myimage.jpg');
        $file->setBody('<image data>');

        $message->attach($file);

        $cid = $file->getId();

        $message->detach($attachment);

        $this->assertRegExp(
            '~^'.
            'Return-Path: <chris@w3style.co.uk>'."\r\n".
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris Corbyn <chris.corbyn@swiftmailer.org>'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: multipart/alternative;'."\r\n".
            ' boundary="'.$boundary.'"'."\r\n".
            "\r\n\r\n".
            '--'.$boundary."\r\n".
            'Content-Type: text/plain; charset=iso-8859-1'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n".
            "\r\n".
            'foo'.
            "\r\n\r\n".
            '--'.$boundary."\r\n".
            'Content-Type: multipart/related;'."\r\n".
            ' boundary="(.*?)"'."\r\n".
            "\r\n\r\n".
            '--\\1'."\r\n".
            'Content-Type: image/jpeg; name=myimage.jpg'."\r\n".
            'Content-Transfer-Encoding: base64'."\r\n".
            'Content-ID: <'.$cid.'>'."\r\n".
            'Content-Disposition: inline; filename=myimage.jpg'."\r\n".
            "\r\n".
            preg_quote(base64_encode('<image data>'), '~').
            "\r\n\r\n".
            '--\\1--'."\r\n".
            "\r\n\r\n".
            '--'.$boundary.'--'."\r\n".
            '$~D',
            $message->toString(),
            '%s: Attachment should have been detached'
            );
    }

    public function testBoundaryDoesNotAppearAfterAllPartsAreDetached()
    {
        $message = $this->createMessage();
        $message->setReturnPath('chris@w3style.co.uk');
        $message->setSubject('just a test subject');
        $message->setFrom([
            'chris.corbyn@swiftmailer.org' => 'Chris Corbyn', ]);

        $id = $message->getId();
        $date = $message->getDate();
        $boundary = $message->getBoundary();

        $part1 = $this->createMimePart();
        $part1->setContentType('text/plain');
        $part1->setCharset('iso-8859-1');
        $part1->setBody('foo');

        $message->attach($part1);

        $part2 = $this->createMimePart();
        $part2->setContentType('text/html');
        $part2->setCharset('iso-8859-1');
        $part2->setBody('test <b>foo</b>');

        $message->attach($part2);

        $message->detach($part1);
        $message->detach($part2);

        $this->assertEquals(
            'Return-Path: <chris@w3style.co.uk>'."\r\n".
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris Corbyn <chris.corbyn@swiftmailer.org>'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString(),
            '%s: Message should be restored to orignal state after parts are detached'
            );
    }

    public function testCharsetFormatOrDelSpAreNotShownWhenBoundaryIsSet()
   