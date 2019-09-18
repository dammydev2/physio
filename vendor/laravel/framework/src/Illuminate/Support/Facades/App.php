<?php

namespace Illuminate\Translation;

use Countable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Translation\Loader;
use Illuminate\Support\NamespacedItemResolver;
use Illuminate\Contracts\Translation\Translator as TranslatorContract;

class Translator extends NamespacedItemResolver implements TranslatorContract
{
    use Macroable;

    /**
     * The loader implementation.
     *
     * @var \Illuminate\Contracts\Translation\Loader
     */
    protected $loader;

    /**
     * The default locale being used by the translator.
     *
     * @var string
     */
    protected $locale;

    /**
     * The fallback locale used by the translator.
     *
     * @var string
     */
    protected $fallback;

    /**
     * The array of loaded translation groups.
     *
     * @var array
     */
    protected $loaded = [];

    