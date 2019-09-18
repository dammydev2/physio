k('C', 0x8F).'bar';

        $encoder = $this->getHeaderEncoder('Q');
        $encoder->shouldReceive('encodeString')
                ->once()
                ->with($value, \Mockery::any(), \Mockery::any(), \Mockery::any())
                ->andReturn('fo=8Fbar');

        $paramEncoder = $this->getParameterEncoder();
        $paramEncoder->shouldReceive('encodeString')
                     ->once()
                     ->with($value, \Mockery::any(), \Mockery::any(), \Mockery::any())
                     ->andReturn('fo%8Fbar');

        $header = $this->getHeader('X-Foo', $encoder, $paramEncoder);
        $header->setLanguage('en');
        $header->setValue($value);
        $header->setParameters(['says' => $value]);
        $this->assertEquals("X-Foo: =?utf-8*en?Q?fo=8Fbar?=; says*=utf-8'en'fo%8Fbar\r\n",
            $header->toString()
            );
    }

    public function testSetBodyModel()
    {
        $header = $this->getHeader('Content-Type',
            $this->getHeaderEncoder('Q', true), $this->getParameterEncoder(true)
            );
        $header->setFieldBodyModel('text/html');
        $this->assertEquals('text/html', $header->getValue());
    }

    public function testGetBodyModel()
    {
        $header = $this->getHeader('Content-Type',
            $this->getHeaderEncoder('Q', true), $this->getParameterEncoder(true)
            );
        $header->setValue('text/plain');
        $this->assertEquals('text/plain', $header->getFieldBodyModel());
    }

    public function testSetParameter()
    {
        $header = $this->getHeader('Content-Type',
            $this->getHeaderEncoder('Q', true), $this->getParameterEncoder(true)
            );
        $header->setParameters(['charset' => 'utf-8', 'delsp' => 'yes']);
        $header->setParameter('delsp', 'no');
        $this->assertEquals(['charset' => 'utf-8', 'delsp' => 'no'],
            $header->getParameters()
            );
    }

    public function testGetParameter()
    {
        $header = $this->getHeader('Content-Type',
            $this->getHeaderEncoder('Q', true), $this->getParameterEncoder(true)
            );
        $header->setParameters(['charset' => 'utf-8', 'delsp' => 'yes']);
        $this->assertEquals('utf-8', $header->getParameter('charset'));
    }

    private function getHeader($name, $encoder, $paramEncoder)
    {
        $header = new Swift_Mime_Headers_ParameterizedHeader($name, $encoder, $paramEncoder);
        $header->setChars