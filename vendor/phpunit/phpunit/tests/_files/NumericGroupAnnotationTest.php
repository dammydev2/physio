<?php
namespace Prettus\Repository\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class RequestCriteria
 * @package Prettus\Repository\Criteria
 */
class RequestCriteria implements CriteriaInterface
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    /**
     * Apply criteria in query repository
     *
     * @param         Builder|Model     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     * @throws \Exception
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $fieldsSearchable = $repository->getFieldsSearchable();
        $search = $this->request->get(config('repository.criteria.params.search', 