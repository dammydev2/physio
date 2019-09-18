<?php

namespace Illuminate\Foundation\Events;

use SplFileInfo;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class DiscoverEvents
{
    /**
     * Get all of the events and listeners by searching the given listener directory.
     *
     * @param  string  $listenerPath
     * @param  string  $basePath
     * @return array
     */
    public static function within($listenerPath, $basePath)
    {
        return collect(static::getListenerEvents(
            (new Finder)->files()->in($listenerPath), $basePath
        ))->mapToDictionary(function ($event, $listener) {
            return [$event => $listener];
        })->all();
    }

    /**
     * Get all of the listeners and their corresponding events.
     *
     * @param  iterable  $listeners
     * @param  string  $