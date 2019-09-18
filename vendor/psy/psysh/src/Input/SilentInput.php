<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Test;

use PhpParser\PrettyPrinter\Standard as Printer;
use Psy\Exception\ParseErrorException;
use Psy\ParserFactory;

class ParserTestCase extends \PHPUnit\Framework\TestCase
{
    protected $traverser;
    private $parser;
    private $printer;

    public function tearDown()
    {
        $this->traverser = null;
        $this->parser = null;
        $this->printer = null;
    }

    protected function parse($code, $prefix = '<?php ')
    {
        $code = $prefix . $code;
        try {
            return $this->getParser()->parse($code);
        } catch (\PhpParser\Error $e) {
            if (!$this->parseErrorIsEOF($e)) {
                throw ParseErrorExcep