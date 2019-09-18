__DIR__.'/../fixtures/non-valid.xlf', 'en', 'domain1');
    }

    /**
     * @expectedException \Symfony\Component\Translation\Exception\NotFoundResourceException
     */
    public function testLoadNonExistingResource()
    {
        $loader = new XliffFileLoader();
        $resource = __DIR__.'/../fixtures/non-existing.xlf';
        $loader->load($resource, 'en', 'domain1');
    }

    /**
     * @expectedException \Symfony\Component\Translation\Exception\InvalidResourceException
     */
    public function testLoadThrowsAnExceptionIfFileNotLocal()
    {
        $loader = new XliffFileLoader();
        $resource = 'http://example.com/resources.xlf';
        $loader->load($resource, 'en', 'domain1');
    }

    /**
     * @expectedException        \Symfony\Component\Translation\Exception\InvalidResourceException
     * @expectedExceptionMessage Document types are not allowed.
     */
    public function testDocTypeIsNotAllowed()
    {
        $loader = new XliffFileLoader();
        $loader->load(__DIR__.'/../fixtures/withdoctype.xlf', 'en', 'domain1');
    }

    public function testParseEmptyFile()
    {
        $loader = new XliffFileLoader();
        $resource = __DIR__.'/../fixtures/empty.xlf';

        if (method_exists($this, 'expectException')) {
            $this->expectException('Symfony\Component\Translation\Exception\InvalidResourceException');
            $this->expectExceptionMessage(sprintf('Unable to load "%s":', $resource));
        } else {
            $this->setExpectedException('Symfony\Component\Translation\Exception\InvalidResourceException', sprintf('Unable to load "%s":', $resource));
        }

        $loader->load($resource, 'en', 'domain1');
    }

    public function testLoadNotes()
    {
        $loader = new XliffFileLoader();
        $catalogue = $loader->load(__DIR__.'/../fixtures/withnote.xlf', 'en', 'domain1');

        $this->assertEquals(['notes' => [['priority' => 1, 'content' => 'foo']], 'id' => '1'], $catalogue->getMetadata('foo', 'domain1'));
        // message without target
        $this->assertEquals(['notes' => [['content' => 'bar', 'from' => 'foo']], 'id' => '2'], $catalogue->getMetadata('extra', 'domain1'));
        // message with empty target
        $this->assertEquals(['notes' => [['content' => 'baz'], ['priority' => 2, 'from' => 'bar', 'content' => 'qux']], 'id' => '123'], $catalogue->getMetadata('key', 'domain1'));
    }

    public function testLoadVersion2()
    {
        $loader = new XliffFileLoader();
        $resource = __DIR__.'/../fixtures/resources-2.0.xlf';
        $catalogue = $loader->load($resource, 'en', 'domain1');

        $this->assertEquals('en', $catalogue->getLocale());
        $this->assertEquals([new FileResource($resource)], $catalogue->getResources());
        $this->assertSame([], libxml_get_errors());

        $domains = $catalogue->all();
        $this->assertCount(3, $domains['domain1']);
        $this->assertContainsOnly('string', $catalogue->all('domain1'));

        // target attributes
        $this->assertEquals(['target-attributes' => ['order' => 1]], $catalogue->getMetadata('bar', 'domain1'));
    }

    public function testLoadVersion2WithNoteMeta()
    {
        $loader = new XliffFileLoader();
        $resource = __DIR__.'/../fixtures/resources-notes-meta.xlf';
        $catalogue = $loader->load($resource, 'en', 'domain1');

        $this->assertEquals('en', $catalogue->getLocale());
        $this->assertEquals([new FileResource($resource)], $catalogue->getResources());
        $this->assertSame([], libxml_get_errors());

        // test for "foo" metadata
        $this->assertTrue($catalogue->defines('foo', 'domain1'));
        $metadata = $catalogue->getMetadata('foo', 'domain1');
        $this->assertNotEmpty($metadata);
        $this->assertCount(3, $metadata['notes']);

        $this->assertEquals('state', $metadata['notes'][0]['category']);
        $this->assertEquals('new', $metadata['note