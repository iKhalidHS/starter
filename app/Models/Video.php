<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = "videos"; // you can change the table names other than model name like it can be "myVideo" based on matching with the table in the database
    protected $fillable =['name','viewers']; // these column only will accept update
    public $timestamps=false;

}
