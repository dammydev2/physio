Resource\ResourceInterface')->getMock();
        $r1->expects($this->any())->method('__toString')->will($this->returnValue('r1'));

        $catalogue = new MessageCatalogue('en', ['domain1' => ['foo' => 'foo']]);
        $catalogue->addResource($r);

        $catalogue1 = new MessageCatalogue('en', ['domain1' => ['foo1' => 'foo1'], 'domain2+intl-icu' => ['bar' => 'bar']]);
        $catalogue1->addResource($r1);

        $catalogue->addCatalogue($catalogue1);

        $this->assertEquals('foo', $catalogue->get('foo', 'domain1'));
        $this->assertEquals('foo1', $catalogue->get('foo1', 'domain1'));
        $this->assertEquals('bar', $catalogue->get('bar', 'domain2'));
        $this->assertEquals('bar', $catalogue->get('bar', 'domain2+intl-icu'));

        $this->assertEquals([$r, $r1], $catalogue->getResources());
    }

    public function testAddFallbackCatalogue()
    {
        $r = $this->getMockBuilder('Symfony\Component\Config\Resource\ResourceInterface')->getMock();
        $r->expects($this->any())->method('__toString')->will($this->returnValue('r'));

        $r1 = $this->getMockBuilder('Symfony\Component\Config\Resource\ResourceInterface')->getMock();
        $r1->expects($this->any())->method('__toString')->will($this->returnValue('r1'));

        $r2 = $this->getMockBuilder('Symfony\Component\Config\Resource\ResourceInterface')->getMock();
        $r2->expects($this->any())->method('__toString')->will($this->returnValue('r2'));

        $catalogue = new MessageCatalogue('fr_FR', ['domain1' => ['foo' => 'foo'], 'domain2' => ['bar' => 'bar']]);
        $catalogue->addResource($r);

        $catalogue1 = new MessageCatalogue('fr', ['domain1' => ['foo' => 'bar', 'foo1' => 'foo1']]);
        $catalogue1->addResource($r1);

        $catalogue2 = new MessageCatalogue('en');
        $catalogue2->addResource($r2);

        $catalogue->addFallbackCatalogue($catalogue1);
      