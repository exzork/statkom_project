<?php

namespace App\Http\Controllers;

use App\Models\data_survey;
use Illuminate\Http\Request;

class data_surveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = data_survey::all();
        return view('content.pengolahan')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\data_survey  $data_survey
     * @return \Illuminate\Http\Response
     */
    public function show(data_survey $data_survey)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\data_survey  $data_survey
     * @return \Illuminate\Http\Response
     */
    public function edit(data_survey $data_survey)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\data_survey  $data_survey
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, data_survey $data_survey)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\data_survey  $data_survey
     * @return \Illuminate\Http\Response
     */
    public function destroy(data_survey $data_survey)
    {
        //
    }
}
