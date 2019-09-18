<?php declare(strict_types=1);

namespace PhpParser\Node\Stmt;

use PhpParser\Node;

class ClassConst extends Node\Stmt
{
    /** @var int Modifiers */
    public $flags;
    /** @var Node\Const_[] Constant declarations */
    public $consts;

    /**
     * Constructs a class const list node.
     *
     * @param Node\Const_[] $consts     Constant declarations
     * @param int           $flags      Modifiers
     * @param array         $attributes Additional attributes
     */
    public function __construct(array $consts, int $flags = 0, array $attributes = []) {
        parent::__construct($attributes);
        $this->flags = $flags;
        $this->consts = $consts;
    }

    public function getSubNodeNames() : array {
        return ['flags', 'consts'];
    }

    /**
     * Whether constant is explicitly or implicitly public.
     *
     * @return bool
     */
