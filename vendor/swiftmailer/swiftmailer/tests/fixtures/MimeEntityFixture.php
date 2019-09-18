sCorrectModel()
    {
        $header = $this->factory->createPathHeader('X-Path', 'foo@bar');
        $this->assertEquals('foo@bar', $header->getFieldBodyModel());
    }

    public function testCharsetChangeNotificationNotifiesEncoders()
    {
        $encoder = $this->createHeaderEncoder();
        $encoder->expects($this->once())
                ->method('charsetChanged')
                ->with('utf-8');
        $paramEncoder = $this->createParamEncoder();
        $paramEncoder->expects($this->once())
                     ->method('charsetChanged')
                     ->with('utf-8');

        $factory = $this->createFactory($encoder, $paramEncoder);

        $factory->charsetChanged('utf-8');
    }

    private function createFactory($encoder = null, $paramEncoder = null)
    {
        return new Swift_Mime_SimpleHeaderFactory(
            $encoder
                ? $encoder : $this->createHeaderEncoder(),
            $paramEncoder
                ? $paramEncoder : $this->createParamEncoder(),
            new EmailValidator()
            );
    }

    private function createHeaderEncoder()
    {
        return $this->getMockBuilder('Swift_Mime_HeaderEncoder')->getMock();
 