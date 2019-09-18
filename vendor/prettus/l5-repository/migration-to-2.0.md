<?php
namespace Prettus\Repository\Presenter;

use Exception;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\SerializerAbstract;
use Prettus\Repository\Contracts\PresenterInterface;

/**
 * Class FractalPresenter
 * @package Prettus\Repository\Presenter
 */
abstract class FractalPresenter implements PresenterInterface
{
    /**
     * @var string
     */
    protected $resourceKeyItem = nu