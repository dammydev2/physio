<?php
/**
 * Whoops - php errors for cool kids
 * @author Filipe Dobreira <http://github.com/filp>
 */

namespace Whoops\Exception;

use InvalidArgumentException;
use Serializable;

class Frame implements Serializable
{
    /**
     * @var array
     */
    protected $frame;

    /**
     * @var string
     */
    protected $fileContentsCache;

    /**
     * @var array[]
     */
    protected $comments = [];

    /**
     * @var bool
     */
    protected $application;

    /**
     * @param array[]
     */
    public function __construct(array $frame)
    {
        $this->frame = $frame;
    }

    /**
     * @param  bool        $shortened
     * @return string|null
     */
    public function getFile($shortened = false)
    {
        if (empty($this->frame['file'])) {
            return null;
        }

        $file = $this->frame['file'];

        // Check if this frame occurred within an eval().
        // @todo: This can be made more reliable by checking if we've entered
        // eval() in a previous trace, but will need some more work on the upper
        // trace collector(s).
        if (preg_match('/^(.*)\((\d+)\) : (?:eval\(\)\'d|assert) code$/', $file, $matches)) {
            $file = $this->frame['file'] = $matches[1];
            $this->frame['line'] = (int) $matches[2];
        }

        if ($shortened && is_string($file)) {
            // Replace the part of the path that all frames have in common, and add 'soft hyphens' for smoother line-breaks.
            $dirname = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
            if ($dirname !== '/') {
                $file = str_replace($dirname, "&hellip;", $file);
            }
            $file = str_replace("/", "/&shy;", $file);
        }

        return $file;
    }

    /**
     * @return int|null
     */
    public function getLine()
    {
        return isset($this->frame['line']) ? $this->frame['line'] : null;
    }

    /**
     * @return string|null
     */
    public function getClass()
    {
        return isset($this->frame['class']) ? $this->frame['class'] : null;
    }

