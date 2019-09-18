<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Framework;

use PHPUnit\Util\Xml;

class AssertTest extends TestCase
{
    public static function validInvalidJsonDataprovider(): array
    {
        return [
            'error syntax in expected JSON' => ['{"Mascott"::}', '{"Mascott" : "Tux"}'],
            'error UTF-8 in actual JSON'    => ['{"Mascott" : "Tux"}', '{"Mascott" : :}'],
        ];
    }

    public function testFail(): void
    {
        try {
            $this->fail();
        } catch (AssertionFailedError $e) {
            return;
        }

        throw new AssertionFailedError('Fail did not throw fail exception');
    }

    public function testAssertSplObjectStorageContainsObject(): void
    {
        $a = new \stdClass;
        $b = new \stdClass;
        $c = new \SplObjectStorage;
        $c->attach($a);

        $this->assertContains($a, $c);

  