rom([
            'chris.corbyn@swiftmailer.org' => 'Chris Corbyn', ]);
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Return-Path: <chris@w3style.co.uk>'."\r\n".
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris Corbyn <chris.corbyn@swiftmailer.org>'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testEmptyReturnPathHeaderCanBeUsed()
    {
        $message = $this->createMessage();
        $message->setReturnPath('');
        $message->setSubject('just a test subject');
        $message->setFrom([
            'chris.corbyn@swiftmailer.org' => 'Chris Corbyn', ]);
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Return-Path: <>'."\r\n".
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris Corbyn <chris.corbyn@swiftmailer.org>'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testSenderCanBeSet()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setSender('chris.corbyn@swiftmailer.org');
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Sender: chris.corbyn@swiftmailer.org'."\r\n".
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: '."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testSenderCanBeSetWithName()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setSender(['chris.corbyn@swiftmailer.org' => 'Chris']);
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Sender: Chris <chris.corbyn@swiftmailer.org>'."\r\n".
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
        