<?php

namespace Shopbox\Http\Controllers\DataTable;

use Exception;
use QueryException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class DataTableController extends Controller
{
    protected $builder;

    protected $paginator;

    /**
     * If an entity is allowed to be deleted.
     * @var  boolean
     */
    
    protected $allowDeletion = false;

    /**
     * If an entity is allowed to be updated.
     * @var  boolean
     */

    protected $allowUpdate = false;

    /**
     * If an entity is allowed to be created.
     * @var  boolean
     */

    protected $allowCreate = false;

    abstract public function builder();

    public function __construct()
    { 
        $builder = $this->builder();
        if(!$builder instanceof Builder) { 
            throw new Exception('Entity builder not instance of Builder');
        }

        $this->builder = $builder;
    }   

    /**
     * get records
     * @return Illuminate\Http\JsonResponse
     */
    public function index(Request $request) 
    { 
        $data = [
            'records' => $this->getRecords($request),
            'allow' => [
                'deletion' => $this->allowDeletion,
                'update' => $this->allowUpdate,
                'create' => $this->allowCreate
            ],
        ];

        if($this->paginator) {
            $data['pagination'] = [
                'count' => $this->paginator->count(),
                'current_page' => $this->paginator->currentPage(),
                'per_page' => $this->paginator->perPage(),
                'total' => $this->paginator->total(),
                'total_pages' => $this->paginator->lastPage(),
                'links' => $this->getLinks($this->paginator)
            ];
        }

        return response()->json([
            'data' => $data
        ]);
    }

    protected function getLinks(LengthAwarePaginator $paginator) 
    {
        $data = [];

        if($paginator->nextPageUrl()) {
            $data['next'] = $paginator->nextPageUrl();
        }

        if($paginator->previousPageUrl()) {
            $data['previous'] = $paginator->previousPageUrl();
        }

        return $data;
    }

    protected function getFilters()
    {
        return [
            //
        ];
    }


    public function destroy ($ids)
    {  
        if(!$this->allowDeletion) {
            return;
        }

        $this->builder->whereIn('id', explode(',', $ids))->delete();

    }

    protected function getRecords(Request $request)
    { 
        $builder = $this->builder;
       
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }
        try {
            return $this->builder->where('store_id', $request->tenant()->id)->orderBy('id', 'desc')->limit($request->limit)->get();
        } catch (QueryException $e) {
            return [];
        }
    }

    protected function hasSearchQuery(Request $request) 
    {
        return count(array_filter($request->only(['column', 'operator', 'value']), function($var) {
                                    return ($var !== NULL && $var !== FALSE && $var !== '');
                                })) === 3;
    }

    protected function buildSearch(Builder $builder, Request $request)
    {
        $queryParts =  $this->resolveQueryparts($request->operator, $request->value);
        return $builder->where($request->column, $queryParts['operator'], $queryParts['value']);
    }

    protected function resolveQueryparts($operator, $value)
    {
        return array_get([
            'equals' => [
                'operator' => '=',
                'value' => $value
            ]
        ], $operator);
    }

    
}
