<?php

namespace Faker\Provider;

/**
 * Depends on image generation from http://lorempixel.com/
 */
class Image extends Base
{
    protected static $categories = array(
        'abstract', 'animals', 'business', 'cats', 'city', 'food', 'nightlife',
        'fashion', 'people', 'nature', 'sports', 'technics', 'transport'
    );

    /**
     * Generate the URL that will return a random image
     *
     * Set randomize to false to remove the random GET parameter at the end of the url.
     *
     * @example 'http://lorempixel.com/640/480/?12345'
     *
     * @param integer $width
     * @param integer $height
     * @param string|null $category
     * @param bool $randomize
     * @param string|null $word
     * @param bool $gray
     *
     * @return string
     */
    public static function imageUrl($width = 640, $height = 480, $category = null, $randomize = true, $word = null, $gray = false)
    {
        $baseUrl = "https://lorempixel.com/";
        $url = "{$width}/{$height}/";

        if ($gray) {
            $url = "gray/" . $url;
        }

        if ($category) {
            if (!in_array($category, static::$categories)) {
                throw new \InvalidArgumentException(sprintf('Unknown image category "%s"', $category));
            }
            $url .= "{$category}/";
            if ($word) {
                $url .= "{$word}/";
            }
        }

        if ($randomize) {
            $url .= '?' . static::randomNumber(5, true);
        }

        return $baseUrl . $url;
    }

    /**
     * Download a remote random image to disk and return its location
     *
     * Requires curl, or allow_url_fopen to be on in php.ini.
     *
     * @example '/path/to/dir/13b73edae8443990be1aa8f1a483bc27.jpg'
     */
    public sta