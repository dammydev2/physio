<?php

namespace Illuminate\Database\Eloquent\Relations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HasManyThrough extends Relation
{
    /**
     * The "through" parent model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $throughParent;

    /**
     * The far parent model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $farParent;

    /**
     * The near key on the relationship.
     *
     * @var string
     */
    protected $firstKey;

    /**
     * The far key on the relationship.
     *
     * @var string
     */
    protected $secondKey;

    /**
     * The local key on the relationship.
     *
     * @var string
     */
    protected $localKey;

    /**
     * The local key on the intermediary model.
     *
     * @var s