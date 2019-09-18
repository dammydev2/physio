<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Output;

use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;

/**
 * Base class for output classes.
 *
 * There are five levels of verbosity:
 *
 *  * normal: no option passed (normal output)
 *  * verbose: -v (more output)
 *  * very verbose: -vv (highly extended output)
 *  * debug: -vvv (all debug output)
 *  * quiet: -q (no output)
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class Output implements OutputInterface
{
    private $verbosity;
    private $formatter;

    /**
     * @param int                           $verbosity The verbosity level (one of the VERBOSITY constants in OutputInterface)
     * @param bool                          $decorated Whether to decorate messages
     * @param OutputFormatterInterface|null $formatter Output formatter instance (null to use default OutputFormatter)
     */
    public function __construct(?int $verbosity = self::VERBOSITY_NORMAL, bool $decorated = false, OutputFormatterInterface $formatter = null)
    {
        $this->verbosity = null === $verbosity ? self::VERBOSITY_NORMAL : $verbosity;
        $this->formatter = $formatter ?: new OutputFormatter();
        $this->formatter->setDecorated($decorated);
    }

    /**
     * {@inheritdoc}
     */
    public function setFormatter(OutputFormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormatter()
    {
        return $this->formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function setDecorated($decorated)
    {
        $this->formatter->setDecorated($decorated);
    }

    /**
     * {@inheritdoc}
     */
    public function 