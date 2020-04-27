<?php

namespace App\Listeners;

use App\Events\VideoViewer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class IncreaseCounter
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(VideoViewer $event) //we are passing the event class by taking an object "$event" from it
    {
        $this->updateViewer($event->video); // we are calling the $video property in the VideoViewer class which we get by making object from videoViewer class, which it's exist in. //note : this value include the model
    }

    public function updateViewer($video){ // we add variable $video because we are passing a value above
        $video -> viewers += 1;
        $video ->save(); //updating the model field directly using save()
    }
}
