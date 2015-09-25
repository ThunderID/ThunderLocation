<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Foundation\Bus\DispatchesJobs;


class DeletingLocation extends Job implements SelfHandling
{
    use DispatchesJobs;
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
        // Check if location has children
        if ($this->model->children->count())
        {
            return JSend::fail(['children' => 'This location has ' . $this->model->children->count() . 'sublocations']);
        }

        // Allow to delete location
        return JSend::success($this->model);
    }
}
