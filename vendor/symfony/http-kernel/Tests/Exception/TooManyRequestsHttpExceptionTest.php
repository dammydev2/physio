', 3, 'GET', $start, time() + 3 * 60);
        $this->assertCount(3, $records, '->find() returns all previously added records');
        $this->assertEquals($records[0]['token'], 'time_2', '->find() returns records ordered by time in descendant order');
        $this->assertEquals($records[1]['token'], 'time_1', '->find() returns records ordered by time in descendant order');
        $this->assertEquals($records[2]['token'], 'time_0', '->find() returns records ordered by time in descendant order');

        $records = $this->storage->find('', '', 3, 'GET', $start, time() + 2 * 60);
        $this->assertCount(2, $records, '->find() should return only first two of the previously added records');
    }

    public function testRetrieveByEmptyUrlAndIp()
    {
        for ($i = 0; $i < 5; ++$i) {
            $profile = new Profile('token_'.$i);
            $profile->setMethod('GET');
            $this->storage->write($profile);
        }
        $this->assertCount(5, $this->storage->find('', '', 10, 'GET'), '->find() returns all previously added records');
        $this->storage->purge();
    }

    public function