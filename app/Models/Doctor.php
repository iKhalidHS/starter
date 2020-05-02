<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = "doctors";
    protected $fillable=['name','title','hospital_id','gender','created_at','updated_at'];
    protected $hidden =['created_at','updated_at'];
    public $timestamps = true;

    public function hospital(){
        return $this->belongsTo('App\Models\hospital','hospital_id','id');
    }
}
