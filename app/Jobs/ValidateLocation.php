<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class ValidateLocation extends Job implements SelfHandling
{
	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(\App\Location $model)
	{
		//
		$this->model = $model;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		// set validation rules
		$rules['name']      =   ['required'];
		$rules['level']     =   ['required', 'in:continent,country,province,city,suburb'];
		$rules['latitude']	= 	['numeric'];
		$rules['longitude']	= 	['numeric'];

		// validates
		$validator = Validator::make($this->model->toArray(), $rules);

		if ($validator->fails)
		{
			return JSend::fail($validator->messages()->toArray());
		}
		else
		{
			return JSend::success($this->model);
		}

	}
}
