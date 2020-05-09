<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = "doctors";
    protected $fillable=['name','title','hospital_id','medical_id','gender','created_at','updated_at'];
    protected $hidden =['created_at','updated_at','pivot'];
    public $timestamps = true;

    public function hospital(){
        return $this->belongsTo('App\Models\hospital','hospital_id','id');
    }

    public function services(){
        return $this -> belongsToMany('App\Models\service','doctor_service','doctor_id','service_id','id','id');
    }


    //// accessors
    public function getGenderAttribute($val){ // we wright the filed name between get and attribute using camel case
        return $val == 1 ? 'male' : 'female';


        /* switch ($val) {
            case "1":
                return "male";
                break; // if you try to use check
            case "2":
                return "Female";
                break;
        } */
    }

}
