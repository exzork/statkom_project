<?php

namespace App\Http\Controllers;

use App\Models\data_survey;
use Illuminate\Http\Request;

use function GuzzleHttp\Promise\all;

class data_surveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sum=0;
        $count=0;
        $sum_o=0;
        $count_o=0;
        $var_p_all=0;
        $stdev_p_all=0;
        $data_mentah = data_survey::get();
        $data_urut= data_survey::orderBy('data')->get();
        foreach($data_mentah as $data_){
            $sum+=$data_['data'];
            $count++;
        }
        $mean_all=$sum/$count;
        foreach($data_mentah as $data_){
            $var_p_all+=pow($data_['data']-$mean_all,2)/$count;
        }
        $stdev_p_all=sqrt($var_p_all);
        $count=0;
        foreach($data_mentah as $data_){
            $data_mentah[$count]['z_score']=($data_['data']-$mean_all)/$stdev_p_all;
            $count++;
        }
        $data=[
            'table_mentah'=>$data_mentah,
            'table_urut'=>$data_urut,
            'mean_all' => $sum/$count
        ];
        return view('content.pengolahan')->with($data);
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
