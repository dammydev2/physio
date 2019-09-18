<?php

namespace Illuminate\Routing;

use Illuminate\Support\Collection;

class SortedMiddleware extends Collection
{
    /**
     * Create a new Sorted Middleware container.
     *
     * @param  array  $priorityMap
     * @param  array|\Illuminate\Support\Collection  $middlewares
     * @return void
     */
    public function __construct(array $priorityMap, $middlewares)
    {
        if ($middlewares instanceof Collection) {
            $middlewares = $middlewares->all();
        }

        $this->items = $this->sortMiddleware($priorityMap, $middlewares);
    }

    /**
     * Sort the middlewares by the given priority map.
     *
     * Each call to this method makes one discrete middleware movement if necessary.
     *
     * @param  array  $priorityMap
     * @param  array  $middlewares
     * @return array
     */
    protected function sortMiddleware($priorityMap, $middlewares)
    {
        $lastIndex = 0;

        foreach ($middlewares as $index => $middleware) {
            if (! is_string($middleware)) {
                continue;
            }

            $stripped = head(expl