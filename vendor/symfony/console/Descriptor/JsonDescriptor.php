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
use Symfony\Component\Console\Exception\LogicException;

/**
 * Defines the styles for a Table.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Саша Стаменковић <umpirsky@gmail.com>
 * @author Dany Maillard <danymaillard93b@gmail.com>
 */
class TableStyle
{
    private $paddingChar = ' ';
    private $horizontalOutsideBorderChar = '-';
    private $horizontalInsideBorderChar = '-';
    private $verticalOutsideBorderChar = '|';
    private $verticalInsideBorderChar = '|';
    private $crossingChar = '+';
    private $crossingTopRightChar = '+';
    private $crossingTopMidChar = '+';
    private $crossingTopLeftChar = '+';
    private $crossingMidRightChar = '+';
    private $crossingBottomRightChar = '+';
    private $crossingBottomMidChar = '+';
    private $crossingBottomLeftChar = '+';
    private $crossingMidLeftChar = '+';
    private $crossingTopLeftBottomChar = '+';
    private $crossingTopMidBottomChar = '+';
    private $crossingTopRightBottomChar = '+';
    private $headerTitleFormat = '<fg=black;bg=white;options=bold> %s </>';
    private $footerTitleFormat = '<fg=black;bg=white;options=bold> %s </>';
    private $cellHeaderFormat = '<info>%s</info>';
    private $cellRowFormat = '%s';
    private $cellRowContentFormat = ' %s ';
    private $borderFormat = '%s';
    private $padType = STR_PAD_RIGHT;

    /**
     * Sets padding character, used for cell padding.
     *
     * @param string $paddingChar
     *
     * @return $this
     */
    public function setPaddingChar($paddingChar)
    {
        if (!$paddingChar) {
            throw new LogicException('The padding char must not be empty');
        }

        $this->paddingChar = $paddingChar;

        return $this;
    }

    /**
     * Gets padding character, used for cell padding.
     *
     * @return string
     */
    public function getPaddingChar()
    {
        return $this->paddingChar;
    }

    /**
     * Sets horizontal border characters.
     *
     * <code>
     * ╔═══════════════╤══════════════════════════╤══════════════════╗
     * 1 ISBN          2 Title                    │ Author           ║
     * ╠═══════════════╪══════════════════════════╪══════════════════╣
     * ║ 99921-58-10-7 │ Divine Comedy            │ Dante Alighieri  ║
     * ║ 9971-5-0210-0 │ A Tale of Two Cities     │ Charles Dickens  ║
     * ║ 960-425-059-0 │ The Lord of the Rings    │ J. R. R. Tolkien ║
     * ║ 80-902734-1-6 │ And Then There Were None │ Agatha Christie  ║
     * ╚═══════════════╧══════════════════════════╧══════════════════╝
     * </code>
     *
     * @param string      $outside Outside border char (see #1 of example)
     * @param string|null $inside  Inside border char (see #2 of example), equals $outside if null
     */
    public function setHorizontalBorderChars(string $outside, string $inside = null): self
    {
        $this->horizontalOutsideBorderChar = $outside;
        $this->horizontalInsideBorderChar = $inside ?? $outside;

        return $this;
    }

    /**
     * Sets horizontal border character.
     *
     * @param string $horizontalBorderChar
     *
     * @return $this
     *
     * @deprecated since Symfony 4.1, use {@link setHorizontalBorderChars()} instead.
     */
    public function setHorizontalBorderChar($horizontalBorderChar)
    {
        @trigger_error(sprintf('The "%s()" method is deprecated since Symfony 4.1, use setHorizontalBorderChars() instead.', __METHOD__), E_USER_DEPRECATED);

        return $this->setHorizontalBorderChars($horizontalBorderChar, $horizontalBorderChar);
    }

    /**
     * Gets horizontal border character.
     *
     * @return string
     *
     * @deprecated since Symfony 4.1, use {@link getBorderChars()} instead.
     */
    public function getHorizontalBorderChar()
    {
        @trigger_error(sprintf('The "%s()" method is deprecated since Symfony 4.1, use getBorderChars() instead.', __METHOD__), E_USER_DEPRECATED);

        return $this->horizontalOutsideBorderChar;
    }

    /**
     * Sets vertical border characters.
     *
     * <code>
     * ╔═══════════════╤══════════════════════════╤══════════════════╗
     * ║ ISBN          │ Title                    │ Author           ║
     * ╠══════�