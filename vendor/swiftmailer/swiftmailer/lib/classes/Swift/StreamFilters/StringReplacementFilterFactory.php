<?php

class Swift_KeyCache_DiskKeyCacheAcceptanceTest extends \PHPUnit\Framework\TestCase
{
    private $cache;
    private $key1;
    private $key2;

    protected function setUp()
    {
        $this->key1 = uniqid(microtime(true), true);
        $this->key2 = uniqid(microtime(true), true);
        $this->cache = new Swift_KeyCache_DiskKeyCache(new Swift_KeyCache_SimpleKeyCacheInputStream(), sys_get_temp_dir());
    }

    public function testStringDataCanBeSetAndFetched()
    {
        $this->cache->setString(
            $this->key1, 'foo', 'test', Swift_KeyCache::MODE_WRITE
            );
        $this->assertEquals('test', $this->cache->getString($this->key1, 'foo'));
    }

    public function testStringDataCanBeOverwritten()
    {
        $this->cache->setString(
            $this->key1, 'foo', 'test', Swift_KeyCache::MODE_WRITE
            );
        $this->cache->setString(
            $this->key1, 'foo', 'whatever', Swift_KeyCache::MODE_WRITE
            );
        $this->assertEquals('whatever', $this->cache->getString($this->key1, 'foo'));
    }

    public function testStringDataCanBeAppended()
    {
        $this->cache->setString(
            $this->key1, 'foo', 'test', Swift_Ke