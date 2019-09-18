<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Runner;

use PHPUnit\Framework\TestCase;
use PHPUnit\Util\PHP\AbstractPhpProcess;

class PhptTestCaseTest extends TestCase
{
    private const EXPECT_CONTENT = <<<EOF
--TEST--
EXPECT test
--FILE--
<?php echo "Hello PHPUnit!"; ?>
--EXPECT--
Hello PHPUnit!
EOF;

    private const EXPECTF_CONTENT = <<<EOF
--TEST--
EXPECTF test
--FILE--
<?php echo "Hello PHPUnit!"; ?>
--EXPECTF--
Hello %s!
EOF;

    private const EXPECTREGEX_CONTENT = <<<EOF
--TEST--
EXPECTREGEX test
--FILE--
<?php echo "Hello PHPUnit!"; ?>
--EXPECTREGEX--
Hello [HPU]{4}[nit]{3}!
EOF;

    private const FILE_SECTION = <<<EOF
<?php echo "Hello PHPUnit!"; ?>

EOF;

    /**
     * @var string
     */
    private $dirname;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var PhptTestCase
     */
    private $testCase;

    /**
     * @var AbstractPhpProcess|\PHPUnit\Framework\MockObject\MockObject
     */
    private $phpProcess;

    protected function setUp(): void
    {
        $this->dirname  = \sys_get_temp_dir();
        $this->filename = $this->dirname . '/phpunit.phpt';
        \touch($this->filename);

        $this->phpProcess = $thi