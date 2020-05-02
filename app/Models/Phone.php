<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $table = "phone"; // you can change the table names other than model name like it can be "myVideo" based on matching with the table in the database
    protected $fillable =['code','phone','user_id']; // these column only will accept update
    protected $hidden =['user_id']; // this will hide it from select queries when dealing with relations (video 75 min 24)
    public $timestamps=false;


    ################# Begin relations #################

    public function user(){ //relationship with user we build it by function
        return $this -> belongsTo('App\User' , 'user_id'); // means this phone is related to one user , the second parameter is the foreign key
    }

    ################# End relations ###################

}
