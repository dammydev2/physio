        $this->assertRegExp(
            '~^'.
            'Return-Path: <chris@w3style.co.uk>'."\r\n".
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris Corbyn <chris.corbyn@swiftmailer.org>'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: multipart/mixed;'."\r\n".
            ' boundary="'.$boundary.'"'."\r\n".
            "\r\n\r\n".
            '--'.$boundary."\r\n".
            'Content-Type: multipart/alternative;'."\r\n".
            ' boundary="(.*?)"'."\r\n".
            "\r\n\r\n".
            '--\\1'."\r\n".
            'Content-Type: text/plain; charset=iso-8859-1'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n".
            "\r\n".
            'foo'.

            "\r\n\r\n".
            '--\\1'."\r\n".
            'Content-Type: multipart/related;'."\r\n".
            ' boundary="(.*?)"'."\r\n".
            "\r\n\r\n".
            '--\\2'."\r\n".
            'Content-Type: image/jpeg; name=myimage.jpg'."\r\n".
            'Content-Transfer-Encoding: base64'."\r\n".
            'Content-ID: <'.$cid.'>'."\r\n".
            'Content-Disposition: inline; filename=myimage.jpg'."\r\n".
            "\r\n".
            preg_quote(base64_encode('<image data>'), '~').
            "\r\n\r\n".
            '--\\2--'."\r\n".
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

    public function testComplexEmbeddingOfContent()
    {
        $message = $this->createMessage();
        $message->setReturnPath('chris@w3style.co.uk');
        $message->setSubject('just a test subject');
        $message->setFrom([
            'chris.corbyn@swiftmailer.org' => 'Chri