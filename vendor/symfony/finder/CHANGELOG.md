<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Finder\Tests\Iterator;

use Symfony\Component\Finder\Iterator\PathFilterIterator;

class PathFilterIteratorTest extends IteratorTestCase
{
    /**
     * @dataProvider getTestFilterData
     */
    public function testFilter(\Iterator $inner, array $matchPatterns, array $noMatchPatterns, array $resultArray)
    {
        $iterator = new PathFilterIterator($inner, $matchPatterns, $noMatchPatterns);
        $this->assertIterator($resultArray, $iterator);
    }

    public function getTestFilterData()
    {
        $inner = new MockFileListIterator();

        //PATH:   A/B/C/abc.dat
        $inner[] = new MockSplFileInfo([
            'name' => 'abc.dat',
            'relativePathname' => 'A'.\DIRECTORY_SEPARATOR.'B'.\DIRECTORY_SEPARATOR.'C'.\DIRECTORY_SEPARATOR.'abc.dat',
        ]);

        //PATH:   A/B/ab.dat
        $inner[] = new MockSplFileInfo([
            'name' => 'ab.dat',
            'relativePathname' => 'A'.\DIRECTORY_SEPARATOR.'B'.\DIRECTORY_SEPARATOR.'ab.dat',
        ]);

        //PATH:   A/a.dat
        $inner[] = new MockSplFileInfo([
            'name' => 'a.dat',
            'relativePathname' => 'A'.\DIRECTORY_SEPARATOR.'a.dat',
        ]);

        //PATH:   copy/A/B/C/abc.dat.copy
        $inner[] = new MockSplFileInfo([
            'name' => 'abc.dat.copy',
            'relativePathname' => 'copy'.\DIRECTORY_SEPARATOR.'A'.\DIRECTORY_SEPARATOR.'B'.\DIRECTORY_SEPARATOR.'C'.\DIRECTORY_SEPARATOR.'abc.dat',
        ]);

        //PATH:   copy/A/B/ab.dat.copy
        $inne