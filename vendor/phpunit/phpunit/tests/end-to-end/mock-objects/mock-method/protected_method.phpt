--TEST--
https://github.com/sebastianbergmann/phpunit/issues/3364
--FILE--
<?php
$_SERVER['argv'][1] = '--no-configuration';
$_SERVER['argv'][2] = '--teamcity';
$_SERVER['argv'][3] = __DIR__ . DIRECTORY_SEPARATOR . 'tests';

require __DIR__ . '/../../../../bootstrap.php';
PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s by Sebastian Bergmann and contributors.


##teamcity[testCount count='4' flowId='%d']

##teamcity[testSuiteStarted name='%stests%eend-to-end%eregression%eGitHub%e3364%etests' flowId='%d']

##teamcity[testSuiteStarted name='Issue3364SetupBeforeClassTest' locationHint='php_qn://%s%etests%eend-to-end%eregression%eGitHub%e3364%etests%eIssue3364SetupBeforeClassTest.php::\Issue3364SetupBeforeClassTest' flowId='%d']

##teamcity[testStarted name='testOneWithClassSetupException' locationHint='php_qn://%s%etests%eend-to-end%eregression%eGitHub%e3364%etests%eIssue3364SetupBeforeClassTest.php::\Issue3364SetupBeforeClassTest::testOneWithClassSetupException' flowId='%d']

##teamcity[testFailed name='testOneWithClassSetupException' messa