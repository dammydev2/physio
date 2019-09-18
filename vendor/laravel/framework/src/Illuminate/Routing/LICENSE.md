<?php

namespace DummyNamespace;

use DummyFullModelClass;
use ParentDummyFullModelClass;
use Illuminate\Http\Request;
use DummyRootNamespaceHttp\Controllers\Controller;

class DummyClass extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \ParentDummyFullModelClass  $ParentDummyModelVariable
     * @return \Illuminate\Http\Response
     */
    public function index(ParentDummyModelClass $ParentDummyModelVariable)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \ParentDummyFullModelClass  $ParentDummyModelVariable
     * @return \Illuminate\Http\Response
     */
    public function create(ParentDummyModelClass $ParentDummyModelVariable)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \ParentDummyFullModelClass  $ParentDummyModelVariable
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ParentDummyModelClass $ParentDum