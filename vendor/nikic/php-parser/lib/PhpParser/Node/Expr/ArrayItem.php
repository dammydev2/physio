<?php declare(strict_types=1);

namespace PhpParser\Node\Stmt;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;

class Property extends Node\Stmt
{
    /** @var int Modifiers */
    public $flags;
    /** @var PropertyProperty[] Properties */
    public $props;
    /** @var null|Identifier|Name|NullableType Type declaration */
    public $type;

    /**
     * Constructs a class property list node.
     *
     * @param int                                      $flags      Modifiers
     * @param PropertyProperty[]                       $props      Properties
     * @param array                                    $attributes Additional attributes
     * @param null|string|Identifier|Name|NullableType $type       Type declaration
     */
    public function __construct(int $flags, array $props, array $attributes = [], $type = null) {
        parent::__construct($attributes);
        $this->flags = $flags;
        