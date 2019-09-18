<?php

namespace Illuminate\Validation;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\Factory as FactoryContract;

class Factory implements FactoryContract
{
    /**
     * The Translator implementation.
     *
     * @var \Illuminate\Contracts\Translation\Translator
     */
    protected $translator;

    /**
     * The Presence Verifier implementation.
     *
     * @var \Illuminate\Validation\PresenceVerifierInterface
     */
    protected $verifier;

    /**
     * The IoC container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * All of the custom validator extensions.
     *
     * @var array
     */
    protected $extensions = [];

    /**
     * All of the custom implicit validator extensions.
     *
     * @var array
     */
    protected $implicitExt