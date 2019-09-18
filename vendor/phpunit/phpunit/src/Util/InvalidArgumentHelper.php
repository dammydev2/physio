--TEST--
phpunit --log-junit php://stdout DataProviderTest ../../_files/DataProviderTest.php
--FILE--
<?php
$_SERVER['argv'][1] = '--no-configuration';
$_SERVER['argv'][2] = '--log-junit';
$_SERVER['argv'][3] = 'php://stdout';
$_SERVER['argv'][4] = 'DataProviderTest';
$_SERVER['argv'][5] = __DIR__ . '/../_files/DataProviderTest.php';

require __DIR__ . '/../bootstrap.php';
PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s by Sebastian Bergmann and contributors.

..F.                                                                4 / 4 (100%)<?xml version="1.0" encoding="UTF-8"?>
<testsuites>
  <testsuite name="DataProviderTest" file="%sDataProviderTest.php" tests="4" assertions="4" errors="0" failures="1" skipped="0" time="%f">
    <testsuite name="DataProviderTest::testAdd" tests="4" assertions="4" errors="0" failures="1" skipped="0" time="%f">
      <testcase name="testAdd with data set #0" class="DataProviderTest" classname="DataProvider