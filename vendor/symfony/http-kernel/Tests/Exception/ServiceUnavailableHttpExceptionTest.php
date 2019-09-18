e() overwrites the current profile data');

        $this->assertCount(1, $this->storage->find('', '', 1000, ''), '->find() does not return the same profile twice');
    }

    public function testRetrieveByIp()
    {
        $profile = new Profile('token');
        $profile->setIp('127.0.0.1');
        $profile->setMethod('GET');
        $this->storage->write($profile);

        $this->assertCount(1, $this->storage->find('127.0.0.1', '', 10, 'GET'), '->find() retrieve a record by IP');
        $this->assertCount(0, $this->storage->find('127.0.%.1', '', 10, 'GET'), '->find() does not interpret a "%" as a wildcard in the IP');
        $this->assertCount(0, $this->storage->find('127.0._.1', '', 10, 'GET'), '->find() does not interpret a "_" as a wildcard in the IP');
    }

    public function testRetrieveByStatusCode()
    {
        $profile200 = new Profile('statuscode200');
        $profile200->setStatusCode(200);
        $this->storage->write($profile200);

        $profile404 = new Profile('statuscode404');
        $profile404->setStatusCode(404);
        $this->storage->write($profile404);

        $this->assertCount(1, $this->st