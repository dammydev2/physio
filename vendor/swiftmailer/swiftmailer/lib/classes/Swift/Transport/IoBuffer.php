is']);
        $message->setReplyTo([
            'chris@w3style.co.uk' => 'Myself',
            'my.other@address.com' => 'Me',
            ]);
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris <chris.corbyn@swiftmailer.org>'."\r\n".
            'Reply-To: Myself <chris@w3style.co.uk>, Me <my.other@address.com>'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plain'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n",
            $message->toString()
            );
    }

    public function testToAddressCanBeSet()
    {
        $message = $this->createMessage();
        $message->setSubject('just a test subject');
        $message->setFrom(['chris.corbyn@swiftmailer.org' => 'Chris']);
        $message->setReplyTo([
            'chris@w3style.co.uk' => 'Myself',
            'my.other@address.com' => 'Me',
            ]);
        $message->setTo('mark@swiftmailer.org');
        $id = $message->getId();
        $date = $message->getDate();
        $this->assertEquals(
            'Message-ID: <'.$id.'>'."\r\n".
            'Date: '.$date->format('r')."\r\n".
            'Subject: just a test subject'."\r\n".
            'From: Chris <chris.corbyn@swiftmailer.org>'."\r\n".
            'Reply-To: Myself <chris@w3style.co.uk>, Me <my.other@address.com>'."\r\n".
            'To: mark@swiftmailer.org'."\r\n".
            'MIME-Version: 1.0'."\r\n".
            'Content-Type: text/plai