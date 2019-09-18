<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpFoundation;

/**
 * Response represents an HTTP response in JSON format.
 *
 * Note that this class does not force the returned JSON content to be an
 * object. It is however recommended that you do return an object as it
 * protects yourself against XSSI and JSON-JavaScript Hijacking.
 *
 * @see https://www.owasp.org/index.php/OWASP_AJAX_Security_Guidelines#Always_return_JSON_with_an_Object_on_the_outside
 *
 * @author Igor Wiedler <igor@wiedler.ch>
 */
class JsonResponse extends Response
{
    protected $data;
    protected $callback;

    // Encode <, >, ', &, and " characters in the JSON, making it also safe to be embedded into HTML.
    // 15 === JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
    const DEFAULT_ENCODING_OPTIONS = 15;

    protected $encodingOptions = self::DEFAULT_ENCODING_OPTIONS;

    /**
     * @param mixed $data    The response data
     * @param int   $status  The response status code
     * @param array $headers An array of response headers
     * @param bool  $json    If the data is already a JSON string
     */
    public function __construct($data = null, int $status = 200, array $headers = [], bool $json = false)
    {
        parent::__construct('', $status, $headers);

        if (null === $data) {
            $data = new \ArrayObject();
        }

        $json ? $this->setJson($data) : $this->setData($data);
    }

    /**
     * Factory method for chainability.
     *
     * Example:
     *
     *     return JsonResponse::create($data, 200)
     *         ->setSharedMaxAge(300);
     *
     * @param mixed $data    The json response data
     * @param int   $status  The response status code
     * @param array $headers An array of response headers
     *
     * @return static
     */
    public static function create($data = null, $status = 200, $headers = [])
    {
        return new static($data, $status, $headers);
    }

    /**
     * Make easier the creation of JsonResponse from raw json.
     */
    public static function fromJsonString($data = null, $status = 200, $headers = [])
    {
        return new static($data, $status, $headers, true);
    }

    /**
     * Sets the JSONP callback.
     *
     * @param string|null $callback The JSONP callback or null to use none
     *
     * @return $this
     *
     * @throws \InvalidArgumentException When the callback name is not valid
     */
    public function setCallback($callback = null)
    {
        if (null !== $callback) {
            // partially taken from http://www.geekality.net/2011/08/03/valid-javascript-identifier/
            // partially taken from https://github.com/willdurand/JsonpCallbackValidator
            //      JsonpCallbackValidator is released under the MIT License. See https://github.com/willdurand/JsonpCallbackValidator/blob/v1.1.0/LICENSE for details.
            //      (c) William Durand <william.durand1@gmail.com>
            $pattern = '/^[$_\p{L}][$_\p{L}\p{Mn}\p{Mc}\p{Nd}\p{Pc}\x{200C}\x{200D}]*(?:\[(?:"(?:\\\.|[^"\\\])*"|\'(?:\\\.|[^\'\\\])*\'|\d+)\])*?$/u';
            $reserved = [
                'break', 'do', 'instanceof', 'typeof', 'case', 'else', 'new', 'var', 'catch', 'finally', 'return', 'vo