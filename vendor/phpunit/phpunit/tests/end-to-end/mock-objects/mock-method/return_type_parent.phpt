--TEST--
https://github.com/sebastianbergmann/phpunit/issues/3396
--FILE--
<?php
$tmpResultCache = tempnam(sys_get_temp_dir(), __FILE__);
file_put_contents($tmpResultCache, file_get_contents(__DIR__ . '/../../../../_files/DataproviderExecutionOrderTest_result_cache.txt'));

$_SERVER['argv'][1] = '--no-configuration';
$_SERVER['argv'][2] = '--order-by=defects';
$_SERVER['argv'][3] = '--debug';
$_SERVER['argv'][4] = '--cache-result';
$_SERVER['argv'][5] = '--cache-result-file=' . $tmpResultCache;
$_SERVER['argv'][6] = \dirname(\dirname(\dirname(__DIR__))) . '/../_files/DataproviderExecutionOrderTest.php';

require __DIR__ . '/../../../../bootstrap.php';
PHPUnit\TextUI\Command::main();

unlink($tmpResultCache);
--EXPECTF--
PHPUnit %s by Sebastian Bergmann and contributors.

Test 'DataproviderExecutionOrderTest::testAddNumbersWithADataprovider with data set "1+1=3" (1, 1, 3)' started
Test 'DataproviderExecutionOrderTest::testAddNumbersWithADataprovider with data set "1+1=3" (1, 1, 3)' ended
Test 'DataproviderExecutionOrderTest::testAddNumbersWithADataprovider with data set "1+2=3" (1, 