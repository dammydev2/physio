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
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\WrappableOutputFormatterInterface;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Provides helpers to display a table.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Саша Стаменковић <umpirsky@gmail.com>
 * @author Abdellatif Ait boudad <a.aitboudad@gmail.com>
 * @author Max Grigorian <maxakawizard@gmail.com>
 * @author Dany Maillard <danymaillard93b@gmail.com>
 */
class Table
{
    private const SEPARATOR_TOP = 0;
    private const SEPARATOR_TOP_BOTTOM = 1;
    private const SEPARATOR_MID = 2;
    private const SEPARATOR_BOTTOM = 3;
    private const BORDER_OUTSIDE = 0;
    private const BORDER_INSIDE = 1;

    private $headerTitle;
    private $footerTitle;

    /**
     * Table headers.
     */
    private $headers = [];

    /**
     * Table rows.
     */
    private $rows = [];

    /**
     * Column widths cache.
     */
    private $effectiveColumnWidths = [];

    /**
     * Number of columns cache.
     *
     * @var int
     */
    private $numberOfColumns;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var TableStyle
     *