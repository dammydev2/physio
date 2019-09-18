<?php

namespace Illuminate\View;

use Exception;
use Throwable;
use ArrayAccess;
use BadMethodCallException;
use Illuminate\Support\Str;
use Illuminate\Support\MessageBag;
use Illuminate\Contracts\View\Engine;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\Support\MessageProvider;
use Illuminate\Contracts\View\View as ViewContract;

class View implements ArrayAccess, ViewContract
{
    use Macroable {
        __call as macroCall;
    }

    /**
     * The view factory instance.
     *
     * @var \Illuminate\View\Factory
     */
    protected $factory;

    /**
     * The engine implementation.
     *
     * @var \Illuminate\Contracts\View\Engine
     */
    protected $engine;

    /**
     * The name of the view.
     *
     * @var string
     */
    protected $view;

    /**
     * The array of view data.
     *
     * @var array
     */
    protected $data;

    /**
     * The path to the view file.
     *
     * @var string
     */
    protected $path;

    /**
     * Create a new view instance.
     *
     * @param  \Illuminate\View\Factory  $factory
     * @param  \Illuminate\Contracts\View\Engine  $engine
     * @param  string  $view
     * @param  string  $path
     * @param  mixed  $da