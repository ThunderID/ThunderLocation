<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use \App\Location;

class LocationController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getIndex()
	{
		$per_page   = Input::get('per_page', 100);
		$page       = Input::get('page', 1);
		$level      = Input::get('level', null);

		$skip       = ($page - 1) * $per_page;

		// validate
		$rules['per_page'] 	= ['numeric', 'between:1,100'];
		$rules['page']		= ['numeric', 'min:1'];
		$rules['level']		= ['in:continent,country,province,city,suburb'];
		$validator = Validator::make(['per_page' => $per_page, 'page' => $page, 'level' => $level]);

		if ($validator->fails())
		{
			return response()->json(JSend::fail($validator->messages()->toArray()));
		}


		//
		$locations = Location::level($level)
								->orderBy('path')
								->skip($skip)
								->take($per_page)
								->get();

		if ($locations->count())
		{
			return response()->json(JSend::success($locations->toArray()));
		}
		else
		{
			return response()->json(JSend::fail(['Data not found']));
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postStore($id = null)
	{
		if ($id)
		{
			$location = Location::find($id);
			if (!$location)
			{
				return response()->json(JSend::fail(['Data not found']));
			}
		}
		else
		{
			$location = new Location;
		}

		//
		$location->fill(Input::all());

		if ($location->save())
		{
			return response()->json(JSend::success($location->toArray()));
		}
		else
		{
			return response()->json(JSend::fail($location->getErrors()->toArray()));
		}

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getShow($id)
	{
		//
		$location = Location::find($id);

		if ($location)
		{
			return response()->json(JSend::success($location->toArray()));
		}
		else
		{
			return response()->json(JSend::fail(['Data not found']));
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function getDelete($id)
	{
		//
		$location = Location::find($id);

		if (!$location)
		{
			return response()->json(JSend::fail(['Data not found']));
		}

		if ($location->delete())
		{
			return response()->json(JSend::success($location->toArray()));
		}
		else
		{
			return response()->json(JSend::fail($location->getErrors()->toArray()));
		}
	}
}
