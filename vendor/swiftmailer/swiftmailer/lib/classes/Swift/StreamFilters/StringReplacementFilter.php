e->setString(
            $this->key1, 'foo', 'test', Swift_KeyCache::MODE_WRITE
            );

        $is = new Swift_ByteStream_ArrayByteStream();

        $this->cache->exportToByteStream($this->key1, 'foo', $is);

        $string = '';
        while (false !== $bytes = $is->read(8192)) {
            $string .= $bytes;
        }

        $this->assertEquals('test', $string);
    }

    public function testKeyCanBeCleared()
    {
        $this->cache->setString(
            $this->key1, 'foo', 'test', Swift_KeyCache::MODE_WRITE
            );
        $this->assertTrue($this->cache->hasKey($this->key1, 'foo'));
        $this->cache->clearKey($this->key1, 'foo');
        $this->assertFalse($this->cache->hasKey($this->key1, 'foo'));
    }

    public function testNsKeyCanBeCleared()
    {
        $this->cache->setString(
            $this->key1, 'foo', 'test', Swift_KeyCache::MODE_WRITE
            );
        $this->cache->setString(
            $this->key1, 'bar', 'xyz', Swift_KeyCache::MODE_WRITE
            );
        $this->assertTrue($this->cache->hasKey($this->key1, 'foo'));
        $this->assertTrue($this->cache->hasKey($this->key1, 'bar'));
        $this->cache->clearAll($this->key1);
        $this->assertFalse($this->cache->hasKey($this->key1, 'foo'));
        $this->assertFalse($this->cache->hasKey($this->key1, 'bar'));
    }

    public function testKeyCacheInputStream()
    {
        $is = $this->cache->getInputByteStream($this->key1, 'foo');
        $is->write('abc');
        $is->write('xyz');
        $this->assertEquals('abcxyz', $this->cache->getString($this->key1, 'foo