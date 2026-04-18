<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ApplicationRequest;

class ApplicationRequestCreated
{
    use Dispatchable, SerializesModels;

    public $request;
    public $request_id; 
    public $request_key;

    /**
     * Create a new event instance.
     *
     * @param  ApplicationRequest  $request
     * @return void
     */
    public function __construct(ApplicationRequest $request)
    {
        $this->request = $request;
        $this->request_id = $request->id;
        $this->request_key = $request->key; 
    }

}