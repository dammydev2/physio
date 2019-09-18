<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Helper;

use Symfony\Component\Console\Exception\InvalidArgumentException;

/**
 * @author Abdellatif Ait boudad <a.aitboudad@gmail.com>
 */
class TableCell
{
    private $value;
    private $options = [
        'rowspan' => 1,
        'colspan' => 1,
    ];

    public function __construct(string $value = '', array $options = [])
    {
        $this->value = $value;

        // check option names
        if ($diff = array_diff(array_keys($options), a