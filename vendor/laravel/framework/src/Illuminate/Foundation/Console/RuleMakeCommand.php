<?php

namespace Illuminate\Foundation\Support\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Events\DiscoverEvents;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [];

    /**
     * Register the application's event listeners.
     *
     * @return void
     */
    public function boot()
    {
        $events = array_merge_recursive(
            $this->discoveredEvents(),
            $this->listens()
        );

        foreach ($events as $event => $listeners) {
            foreach (array_unique($listeners) as $listener) {
                Event::listen($event, $listener);
            }
        }

        foreac