<?php

namespace Illuminate\Queue;

use Illuminate\Contracts\Queue\QueueableEntity;
use Illuminate\Contracts\Database\ModelIdentifier;
use Illuminate\Contracts\Queue\QueueableCollection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

trait SerializesAndRestoresModelIdentifiers
{
    /**
     * Get the property value prepared for serialization.
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected function getSerializedPropertyValue($value)
    {
        if ($value instanceof QueueableCollection) {
            return new ModelIdentifier(
                $value->getQueueableClass(),
                $value->getQueueableIds(),
                $value->getQueueableRelations(),
                $value->getQueueableConnection()
            );
        }

        if ($value instanceof QueueableEntity) {
            return new ModelIdentifier(
                get_class($value),
                $value->getQueueableId(),
                $value->getQueueableRelations(),
                $value->getQueueableConnection()
            );
        }

        return $value;
    }

    /**
     * G