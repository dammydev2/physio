<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Processes bytes as they pass through a buffer and replaces sequences in it.
 *
 * This stream filter deals with Byte arrays rather than simple strings.
 *
 * @author  Chris Corbyn
 */
class Swift_StreamFilters_ByteArrayReplacementFilter implements Swift_StreamFilter
{
    /** The replacement(s) to make */
    private $replace;

    /** The Index for searching */
    private $index;

    /** The Search Tree */
    private $tree = [];

    /**  Gives the size of the largest search */
    private $treeMaxLen = 0;

    private $repSize;

    /**
     * Create a new ByteArrayReplacementFilter with $search and $replace.
     *
     * @param array $search
     * @param array $replace
     */
    public function __construct($search, $replace)
    {
        $this->index = [];
        $this->tree = [];
        $this->replace = [];
        $this->repSize = [];

        $tree = null;
        $i = null;
        $last_size = $size = 0;
        foreach ($search as $i => $search_element) {
            if (null !== $tree) {
                $tree[-1] = min(count($replace) - 1, $i - 1);
                $tree[-2] = $last_size;
            }
            $tree = &$this->tree;
            if (is_array($search_element)) {
                foreach ($search_element as $k => $char) {
                    $this->index[$char] = true;
           