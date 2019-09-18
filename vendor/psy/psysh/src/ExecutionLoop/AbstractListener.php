<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Util;

/**
 * String utility methods.
 *
 * @author ju1ius
 */
class Str
{
    const UNVIS_RX = <<<'EOS'
/
    \\(?:
        ((?:040)|s)
        | (240)
        | (?: M-(.) )
        | (?: M\^(.) )
        | (?: \^(.) )
    )
/xS
EOS;

    /**
     * Decodes a string encoded by libsd's strvis.
     *
     * From `man 3 vis`:
     *
     * Use an ‘M’ to represent meta characters (characters with the 8th bit set),
     * and use a caret ‘^’ to represent control characters (see iscntrl(3)).
     * The following formats are used:
     *
     *      \040    Represents ASCII space.
     *
     *      \240    Represents Meta-space (&nbsp in HTML).
     *
     *      \M-C    Represents character ‘C’ wi