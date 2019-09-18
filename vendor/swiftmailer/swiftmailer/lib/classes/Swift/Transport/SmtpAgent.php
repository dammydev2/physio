9-1'."\r\n".
            'Content-Transfer-Encoding: quoted-printable'."\r\n".
            "\r\n".
            'foo'.
            "\r\n\r\n".
            '--'.$boundary."\r\n".
            'Content-Type: application/pdf; name=foo.pdf'."\r\n".
            'Content-Transfer-Encoding: base64'."\r\n".
            'Content-Disposition: attachment; filename=foo.pdf'."\r\n".
            "\r\n".
            base64_encode('<pdf data>').
            "\r\n\r\n".
            '--'.$boundary.'--'."\r\n",
            $message->toString()
            );
    }

    public function testHtmlPartAlwaysAppearsLast()
    {
        $message = $this->createMessage();
        $message->setReturnPath('chris@w3style.co.uk');
        $message->setSubject('just a test subject');
        $message->setFrom([
            'chris.corbyn@swiftmailer.org' => 'Chris Corbyn', ]);

        $id = $message->getId();
        $date = $message->getDate()->format('r');
        $boundary = $message->ge