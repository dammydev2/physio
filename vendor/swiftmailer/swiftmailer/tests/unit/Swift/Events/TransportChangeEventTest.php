an 75 characters long, including
        'charset', 'encoding', 'encoded-text', and delimiters.
        */

        $name = 'C'.pack('C', 0x8F).'rbyn';

        $encoder = $this->getEncoder('Q');
        $encoder->shouldReceive('encodeString')
                ->once()
                ->with($name, \Mockery::any(), \Mockery::any(), \Mockery::any())
                ->andReturn('C=8Frbyn');

        $header = $this->getHeader('From', $encoder);
        $header->setNameAddresses(['chris@swiftmailer.org' => 'Chris '.$name]);

        $header->getNameAddressStrings();
    }

    public function testGetValueReturnsMailboxStringValue()
    {
        $header = $this->getHeader('From');
        $header->setNameAddresses([
            'chris@swiftmailer.org' => 'Chris Corbyn',
            ]);
        $this->assertEquals(
