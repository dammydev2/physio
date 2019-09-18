<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\BaseTestRunner;
use PHPUnit\Runner\TestResultCache;

/**
 * @group test-reorder
 */
class TestResultCacheTest extends TestCase
{
    public function testReadsCacheFromProvidedFilename(): void
    {
        $cacheFile = TEST_FILES_PATH . '/MultiDependencyTest_result_cache.txt';
        $cache     = new TestResultCache($cacheFile);
        $cache->load();

        $this->assertSame(BaseTestRunner::STATUS_UNKNOWN, $cache->getState(\MultiDependencyTest::class . '::testOne'));
        $this->assertSame(BaseTestRunner::STATUS_SKIPPED, $cache->getState(\MultiDependencyTest::class . '::testFive'));
    }

    public function testDoesClearCacheBeforeLoad(): void
    {
        $cacheFile = TEST_FILES_PATH . '/MultiDependencyTest_result_cache.txt';
        $cache     = new TestResultCache($cacheFile);
        $cache->setState('someTest', BaseTestRunner::STATUS_FAILURE);

        $this->assertSame(BaseTestRunner::STATUS_UNKNOWN, $cache->getState(\MultiDependencyTest::class . '::testFive'));

        $cache->load();

        $this->assertSame(BaseTestRunner::STATUS_UNKNOWN, $cache->getState(\MultiDependencyTest::class . '::someTest'));
        $this->assertSame(BaseTestRunner::STATUS_SKIPPED, $cache->getState(\MultiDependencyTest::class . '::testFive'));
    }

    public function testShouldNotSerializePassedTestsAsDefectButTimeIsStored(): void
    {
        $cache = new TestResultCache;
        $cache->setState('testOne', BaseTestRunner::STAT