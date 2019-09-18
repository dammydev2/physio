<?php declare(strict_types=1);

namespace PhpParser\Node;

use PhpParser\NodeAbstract;

class Name extends NodeAbstract
{
    /**
     * @var string[] Parts of the name
     */
    public $parts;

    private static $specialClassNames = [
        'self'   => true,
        'parent' => true,
        'static' => true,
    ];

    /**
     * Constructs a name node.
     *
     * @param string|string[]|self $name       Name as string, part array or Name instance (copy ctor)
     * @param array                $attributes Additional attributes
     */
    public function __construct($name, array $attributes = []) {
        parent::__construct($attributes);
        $this->parts = self::prepareName($name);
    }

    public function getSubNodeNames() : array {
        return ['parts'];
    }

    /**
     * Gets the first part of the name, i.e. everything before the first namespace separator.
     *
     * @return string First part of the name
     */
    public function getFirst() : string {
        return $this->parts[0];
    }

    /**
     * Gets the last part of the name, i.e. everything after the last namespace separator.
     *
     * @return string Last part of the name
     */
    public function getLast() : string {
        return $this->parts[count($this->parts) - 1];
    }

    /**
     * Checks whether the name is unqualified. (E.g. Name)
     *
     * @return bool Whether the name is unqualified
     */
    public function isUnqualified() : bool {
        return 1 === count($this->parts);
    }

    /**
     * Checks whether the name is qualified. (E.g. Name\Name)
     *
     * @return bool Whether the name is qualified
     */
    public function isQualified() : bool {
        return 1 < count($this->parts);
    }

    /**
     * Checks whether the name is fully qualified. (E.g. \Name)
     *
     * @return bool Whether the name is fully qualified
     */
    public function isFullyQualified() : bool {
        return false;
    }

    /**
     * Checks whether the name is explicitly relative to the current namespace. (E.g. namespace\Name)
     *
     * @return bool Whether the name is relative
     */
    public function isRelative() : bool {
        return false;
    }

    /**
     * Returns a string representation of the name itself, without taking taking the name type into
     * account (e.g., not including a leading backslash for fully qualified names).
     *
     * @return string String represen