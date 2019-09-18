<?php

namespace Collective\Html;

use BadMethodCallException;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Routing\UrlGenerator;

class HtmlBuilder
{
    use Macroable, Componentable {
        Macroable::__call as macroCall;
        Componentable::__call as componentCall;
    }

    /**
     * The URL generator instance.
     *
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     */
    protected $url;

    /**
     * The View Factory instance.
     *
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;

    /**
     * Create a new HTML builder instance.
     *
     * @param \Illuminate\Contracts\Routing\UrlGenerator $url
     * @param \Illuminate\Contracts\View\Factory         $view
     */
    public function __construct(UrlGenerator $url = null, Factory $view)
    {
        $this->url = $url;
        $this->view = $view;
    }

    /**
     * Convert an HTML string to entities.
     *
     * @param string $value
     *
     * @return string
     */
    public function entities($value)
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
    }

    /**
     * Convert entities to HTML characters.
     *
     * @param string $value
     *
     * @return string
     */
    public function decode($value)
    {
        return html_entity_decode($value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate a link to a JavaScript file.
     *
     * @param string $url
     * @param array  $attributes
     * @param bool   $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function script($url, $attributes = [], $secure = null)
    {
        $attributes['src'] = $this->url->asset($url, $secure);

        return $this->toHtmlString('<script' . $this->attributes($attributes) . '></script>');
    }

    /**
     * Generate a link to a CSS file.
     *
     * @param string $url
     * @param array  $attributes
     * @param bool   $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function style($url, $attributes = [], $secure = null)
    {
        $defaults = ['media' => 'all', 'type' => 'text/css', 'rel' => 'stylesheet'];

        $attributes = array_merge($defaults, $attributes);

        $attributes['href'] = $this->url->asset($url, $secure);

        return $this->toHtmlString('<link' . $this->attributes($attributes) . '>');
    }

    /**
     * Generate an HTML image element.
     *
     * @param string $url
     * @param string $alt
     * @param array  $attributes
     * @param bool   $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function image($url, $alt = null, $attributes = [], $secure = null)
    {
        $attributes['alt'] = $alt;

        return $this->toHtmlString('<img src="' . $this->url->asset($url,
            $secure) . '"' . $this->attributes($attributes) . '>');
    }

    /**
     * Generate a link to a Favicon file.
     *
     * @param string $url
     * @param array  $attributes
     * @param bool   $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function favicon($url, $attributes = [], $secure = null)
    {
        $defaults = ['rel' => 'shortcut icon', 'type' => 'image/x-icon'];

        $attributes = array_merge($attributes, $defaults);

        $attributes['href'] = $this->url->asset($url, $secure);

        return $this->toHtmlString('<link' . $this->attributes($attributes) . '>');
    }

    /**
     * Generate a HTML link.
     *
     * @param string $url
     * @param string $title
     * @param array  $attributes
     * @param bool   $secure
     * @param bool   $escape
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function link($url, $title = null, $attributes = [], $secure = null, $escape = true)
    {
        $url = $this->url->to($url, [], $secure);

        if (is_null($title) || $title === false) {
            $title = $url;
        }

        if ($escape) {
            $title = $this->entities($title);
        }

        return $this->toHtmlString('<a href="' . $this->entities($url) . '"' . $this->attributes($attributes) . '>' . $title . '</a>');
    }

    /**
     * Generate a HTTPS HTML link.
     *
     * @param string $url
     * @param string $title
     * @param array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function secureLink($url, $t