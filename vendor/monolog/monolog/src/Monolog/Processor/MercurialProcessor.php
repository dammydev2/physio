-10seconds');
        $handler->handle($record);
        $record2 = $this->getRecord(Logger::CRITICAL);
        $record2['datetime']->modify('-1day -10seconds');
        $handler->handle($record2);
        $record3 = $this->getRecord(Logger::CRITICAL);
        $record3['datetime']->modify('-30seconds');
        $handler->handle($record3);

        // log is written as none of them are duplicate
        $handler->flush();
        $this->assertSame(
            $record['datetime']->getTimestamp() . ":ERROR:test\n" .
            $record2['datetime']->getTimestamp() . ":CRITICAL:test\n" .
            $record3['datetime']->getTimestamp() . ":CRITICAL:test\n",
            file_get_contents(sys_get_temp_dir() . '/monolog_dedup.log')
        );
        $this->assertTrue($test->hasErrorRecords());
        $this->assertTrue($test->hasCriticalRecords());
        $this->assertFalse($test->hasWarningRecords());

        // clear test handler
        $test->clear();
        $this->assertFalse($test->hasErrorRecords());
        $this->assertFalse($test->hasCriticalRecords());

        // log new records, duplicate log gets GC'd at the end of this flush call
        $handler->handle($record = $this->getRecord(Logger::ERROR));
        $handler->handle($record2 = $this->getRecord(Logger::CRITICAL));
        $handler->flush();

        // log should now contain the new errors and the previous one that was r