xt/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testMultipleCcAddressesCanBeSet()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setFrom(['chris.corbyn@swiftmailer.org' => 'Chris']);
        $message->setReplyTo([
            'chris@w3style.co.uk' => 'Myself',
            'my.other@address.com' => 'Me',
            ]);
        $message->setTo([
            'mark@swiftmailer.org', 'chris@swiftmailer.org' => 'Chris Corbyn',
            ]);
        $message->setCc([
            'john@some-site.com' => 'John West',
            'fred@another-site.co.uk' => 'Big Fred',
            ]);
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris <chris.corbyn@swiftmailer.org>'."\r\n".
            'Reply-To: Myself <chris@w3style.co.uk>, Me <my.other@address.com>'."\r\n".
            'To: mark@swiftmailer.org, Chris Corbyn <chris@swiftmailer.org>'."\r\n".
            'Cc: John West <john@some-site.com>, Big Fred <fred@another-site.co.uk>'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testBccAddressCanBeSet()
    {
        //Obviously Transports need to setBcc(array()) and send to each Bcc recipient
        // separately in accordance with RFC 2822/2821
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setFrom(['chris.corbyn@swiftmailer.org' => 'Chris']);
        $message->setReplyTo([
            'chris@w3style.co.uk' => 'Myself',
            'my.other@address.com' => 'Me',
            ]);
        $message->setTo([
            'mark@swiftmailer.org', 'chris@swiftmailer.org' => 'Chris Corbyn',
            ]);
        $message->setCc([
            'john@some-site.com' => 'John West',
            'fred@another-site.co.uk' => 'Big Fred',
            ]);
        $message->setBcc('x@alphabet.tld');
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris <chris.corbyn@swiftmailer.org>'."\r\n".
            'Reply-To: Myself <chris@w3style.co.uk>, Me <my.other@address.com>'."\r\n".
            'To: mark@swiftmailer.org, Chris Corbyn <chris@swiftmailer.org>'."\r\n".
            'Cc: John West <john@some-site.com>, Big Fred <fred@another-site.co.uk>'."\r\n".
            'Bcc: x@alphabet.tld'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testMultipleBccAddressesCanBeSet()
    {
        //Obviously Transports need to setBcc(array()) and send to each Bcc recipient
        // separately in accordance with RFC 2822/2821
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setFrom(['chris.corbyn@swiftmailer.org' => 'Chris']);
        $message->setReplyTo([
            'chris@w3style.co.uk' => 'Myself',
            'my.other@address.com' => 'Me',
            ]);
        $message->setTo([
            'mark@swiftmailer.org', 'chris@swiftmailer.org' => 'Chris Corbyn',
            ]);
        $message->setCc([
            'john@some-site.com' => 'John West',
            'fred@another-site.co.uk' => 'Big Fred',
            ]);
        $message->setBcc(['x@alphabet.tld', 'a@alphabet.tld' => 'A']);
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris <chris.corbyn@swiftmailer.org>'."\r\n".
            'Reply-To: Myself <chris@w3style.co.uk>, Me <my.other@address.com>'."\r\n".
            'To: mark@swiftmailer.org, Chris Corbyn <chris@swiftmailer.org>'."\r\n".
            'Cc: John West <john@some-site.com>, Big Fred <fred@another-site.co.uk>'."\r\n".
            'Bcc: x@alphabet.tld, A <a@alphabet.tld>'."\r\n".
     