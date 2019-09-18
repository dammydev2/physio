<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\Tests\Profiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Profiler\FileProfilerStorage;
use Symfony\Component\HttpKernel\Profiler\Profile;

class FileProfilerStorageTest extends TestCase
{
    private $tmpDir;
    private $storage;

    protected function setUp()
    {
        $this->tmpDir = sys_get_temp_dir().'/sf_profiler_file_storage';
        if (is_dir($this->tmpDir)) {
            self::cleanDir();
        }
        $this->storage = new FileProfilerStorage('file:'.$this->tmpDir);
        $this->storage->purge();
    }

    protected function tearDown()
    {
        self::cleanDir();
    }

    public function testStore()
    {
        for ($i = 0; $i < 10; ++$i) {
            $profile = new Profile('token_'.$i);
         