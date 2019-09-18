<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Translation\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Resource\SelfCheckingResourceInterface;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\Translator;

class TranslatorCacheTest extends TestCase
{
    protected $tmpDir;

    protected function setUp()
    {
        $this->tmpDir = sys_get_temp_dir().'/sf_translation';
        $this->deleteTmpDir();
    }

    protected function tearDown()
    {
        $this->deleteTmpDir();
    }

    protected function deleteTmpDir()
    {
        if (!file_exists($dir = $this->tmpDir)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->tmpDir), \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($iterator as $path) {
            if (preg_match('#[/\\\\]\.\.?$#', $path->__toString())) {
                continue;
            }
            if ($path->isDir()) {
                rmdir($path->__toString());
            } else {
                unlink($path->__toString());
            }
        }
        rmdir($this->tmpDir);
    }

    /**
     * @dataProvider runForDebugAndProduction
     */
    public function testThatACacheIsUsed($debug)
    {
        $locale = 'any_locale';
        $format = 'some_format';
        $msgid = 'test';

        // Prime the cache
        $translator = new Translator($locale, null, $this->tmpDir, $debug);
     