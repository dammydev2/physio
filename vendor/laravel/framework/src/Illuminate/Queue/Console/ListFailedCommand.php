<?php

namespace Illuminate\Routing;

use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Contracts\Routing\ResponseFactory as FactoryContract;

class ResponseFactory implements FactoryContract
{
    use Macroable;

    /**
     * The view factory instance.
     *
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;

    /**
     * The redirector instance.
     *
     * @var \Illuminate\Routing\Redirector
     */
    protected $redirector;

    /**
     * Create a new response factory instance.
     *
     * @param  \Illuminate\Contracts\View\Factory  $view
     * @param  \Illuminate\Routing\Redirector  $redirector
     * @return void
     */
    public function __construct(ViewFactory $view, Redirector $redirector)
    {
        $this->view = $view;
        $this->redirector = $redirector;
    }

    /**
     * Create a new response instance.
     *
     * @param  string  $content
     * @param  int  $status
     * @param  array  $headers
     * @return \Illuminate\Http\Response
     */
    public function make($content = '', $status = 200, array $headers = [])
    {
        return new Response($content, $status, $headers);
    }

    /**
     * Create a new "no content" response.
     *
     * @param  int  $status
     * @param  array  $headers
     * @return \Illuminate\Http\Response
     */
    public function noContent($status = 204, array $headers = [])
    {
        return $this->make('', $status, $headers);
    }

    /**
     * Create a new response for a given view.
     *
     * @param  string  $view
     * @param  array  $data
     * @param  int  $status
     * @param  array  $headers
     * @return \Illuminate\Http\Response
     */
    public function view($view, $data = [], $status = 200, array $headers = [])
    {
        return $this->make($this->view->make($view, $data), $status, $headers);
    }

    /**
     * Create a new JSON response instance.
     *
     * @param  mixed  $data
     * @param  int  $status
     * @param  array  $headers
     * @param  int  $options
     * @return \Illuminate\Http\JsonResponse
     */
    public function json($data = [], $status = 200, array $headers = [], $options = 0)
    {
        return new JsonResponse($data, $status, $headers, $options);
    }

    /**
     * Create a new JSONP response instance.