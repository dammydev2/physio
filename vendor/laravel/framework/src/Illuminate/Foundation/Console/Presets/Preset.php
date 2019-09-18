<?php

namespace Illuminate\Foundation\Testing;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Foundation\Testing\Assert as PHPUnit;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Foundation\Testing\Constraints\SeeInOrder;

/**
 * @mixin \Illuminate\Http\Response
 */
class TestResponse
{
    use Macroable {
        __call as macroCall;
    }

    /**
     * The response to delegate to.
     *
     * @var \Illuminate\Http\Response
     */
    public $baseResponse;

    /**
     * The streamed content of the response.
     *
     * @var string
     */
    protected $streamedContent;

    /**
     * Create a new test response instance.
     *
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function __construct($response)
    {
        $this->baseResponse = $response;
    }

    /**
     * Create a new TestResponse from another response.
     *
     * @param  \Illuminate\Http\Response  $response
     * @return static
     */
    public static function fromBaseResponse($response)
    {
        return new static($response);
    }

    /**
     * Assert that the response has a successful status code.
     *
     * @return $this
     */
    public function assertSuccessful()
    {
        PHPUnit::assertTrue(
            $this->isSuccessful(),
            'Response status code ['.$this->getStatusCode().'] is not a successful status code.'
        );

        return $this;
    }

    