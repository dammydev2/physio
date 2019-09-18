<?php

namespace Illuminate\Http\Resources\Json;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Responsable;

class ResourceResponse implements Responsable
{
    /**
     * The underlying resource.
     *
     * @var mixed
     */
    public $resource;

    /**
     * Create a new resource response.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
        return tap(response()->json(
            $this->wrap(
                $this->resource->resolve($request),
                $this->resource->with($request),
                $this->resource->additional
            ),
            $this->calculateStatus()
        ), function ($response) use ($request) {
            $response->original = $this->resource->resource;

            $this->resource->withResponse($request, $response);
        });
    }

    /**
     * Wrap the given data if necessary.
     *
     * @param  array  $data
     * @param  array  $with
     * @param  array  $additional
     * @return array
     */
    protected function wrap($data, $with = [], $additional = [])
    {
        if ($data instanceof Collection) {
            $data = $data->all();
        }

        if ($this->haveDefaultWrapperAndDataIsUnwrapped($data)) {
            $data = [$this->wrapper() => $data];
        } elseif ($this->haveAdditionalInformationAndDataIsUnwrapped($data, $with, $additional)) {
    