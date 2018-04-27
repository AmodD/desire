<?php

namespace App\Http\Controllers;

use App\Situation;
use App\Scenario;
use Illuminate\Http\Request;
use DB;

class SituationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    return Situation::all();
	   
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
	    $situation = new Situation();
	    $situation->name = $request->name ;
	    $situation->save();

	    $pin = $request->pinQ;	

	    DB::table('scenario_situation')->insert([
		['situation_id' => $situation->id , 'scenario_id' => $request->whereQ  ],
		['situation_id' => $situation->id , 'scenario_id' => $request->whatQ  ],
		['situation_id' => $situation->id , 'scenario_id' => $request->howQ  ],
		['situation_id' => $situation->id , 'scenario_id' => $request->whoQ  ],
		['situation_id' => $situation->id , 'scenario_id' => $request->whyQ  ],
		['situation_id' => $situation->id , 'scenario_id' => ($request->pinQ) ? $request->pinQ : 0],
	    ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Situation  $situation
     * @return \Illuminate\Http\Response
     */
    public function show(Situation $situation)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Situation  $situation
     * @return \Illuminate\Http\Response
     */
    public function edit(Situation $situation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Situation  $situation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Situation $situation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Situation  $situation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Situation $situation)
    {
        //
    }
}
