<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SavingLocation extends Job implements SelfHandling
{
    use DispatchesJobs;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model)
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
        // validate location
        return $this->dispatch(new ValidateLocation($model));
    }
}
