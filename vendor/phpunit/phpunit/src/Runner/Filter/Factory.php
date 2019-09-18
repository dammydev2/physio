<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Util;

use PHPUnit\Framework\Exception;

/**
 * Utility class that can print to STDOUT or write to a file.
 */
class Printer
{
    /**
     * If true, flush output after every write.
     *
     * @var bool
     */
    protected $autoFlush = false;

    /**
     * @var resource
     */
    protected $out;

    /**
     * @var string
     */
    protected $outTarget;

    /**
     * Constructor.
     *
     * @param null|mixed $out
     *
     * @throws Exception
     */
    public function __construct($out = null)
    {
        if ($out !== null) {
            if (\is_string($out)) {
                if (\strpos($out, 'socket://') === 0) {
                    $out = \explode(':', \str_replace('socket://', '', $out));

                    if (\count($out) !== 2) {
                        throw new Exception;
                    }

                    $this->out = \fsockopen($out[0], $out[1]);
                } else {
                    if (\strpos($out, 'php://') === false 