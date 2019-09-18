<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\VarDumper\Tests\Caster;

use PHPUnit\Framework\TestCase;
use Symfony\Component\VarDumper\Test\VarDumperTestTrait;

/**
 * @author Baptiste Clavié <clavie.b@gmail.com>
 */
class XmlReaderCasterTest extends TestCase
{
    use VarDumperTestTrait;

    /** @var \XmlReader */
    private $reader;

    protected function setUp()
    {
        $this->reader = new \XmlReader();
        $this->reader->open(__DIR__.'/../Fixtures/xml_reader.xml');
    }

    protected function tearDown()
    {
        $this->reader->close();
    }

    public function testParserProperty()
    {
        $this->reader->setParserProperty(\XMLReader::SUBST_ENTITIES, true);

        $expectedDump = <<<'EODUMP'
XMLReader {
  +nodeType: NONE
  parserProperties: {
    SUBST_ENTITIES: true
     …3
  }
   …12
}
EODUMP;

        $this->assertDumpMatchesFormat($expectedDump, $this->reader);
    }

    /**
     * @dataProvider provideNodes
     */
    public function testNodes($seek, $expectedDump)
    {
        while ($seek--) {
            $this->reader->read();
        }
        $this->assertDumpMatchesFormat($expectedDump, $this->reader);
    }

    public function provideNodes()
    {
        return [
            [0, <<<'EODUMP'
XMLRea