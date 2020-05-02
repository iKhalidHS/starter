<?php

namespace App\Http\Controllers\Relation;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Phone;
use App\User;
use Illuminate\Http\Request;

class RelationsController extends Controller
{
    public function hasOneRelation(){
        $user = User::with(['phone'=>function($q){ //phone is the relation name
            $q->select('code','phone','user_id'); //this way to select specific 'phone' fields // note you must add the foreign key field in this way, but you still can hide it from [ protected $hidden =['user_id']  ] in the model
        }])->find(3);

        //return $user = User::find(3)->phone; // this will return the user phone based on the relation named 'phone' in the user model
        //$user = User::with('phone')->find(3); // this will return ajson of all fields of user and phone tables //use 'with()' to return the user row + the phone row related , note 'with()' takes the relation named in user model
        // return $user -> phone ->code; // will return the code only
        return response()->json($user);
    }

    public function hasOneRelationReverse (){
        $phone = Phone::with(['user' =>function($q){
            $q->select('id','name','email');
        }])->find(1);

        //Make some table fields visible in the level of the current function only using [  makeVisible(['anyHiddenField','anyHiddenField','anyHiddenField'])  ]
        //$phone -> makeVisible(['user_id']);

        //Make some table fields hidden in the level of the current function only using [  makeHidden(['anyVisibleField','anyVisibleField','anyVisibleField'])  ]
        //$phone -> makeHidden(['code']);

        return $phone; //return user of this phone number
    }


    public function getUserHasPhone(){
        $user = User::whereHas('phone')->get();        //'phone' is the relationship name in the user model
        return $user;
    }

    public function getUserWhereHasPhoneWithCondition(){
        $user = User::whereHas('phone',function ($q){
            $q->where(['code'=>'02' , 'phone'=>'0105664565']);                      // where the code is equal to '02' and the phone nu,ber is '0105664565'
        })->get(); //'phone' is the relationship name in the user model
        return $user;
    }

    public function getUserNotHasPhone(){
    $user = User::whereDoesntHave('phone')->get(); //'phone' is the relationship name in the user model
    return $user;
    }


    ############################## one To many Relationship methods ########################

    public function getHospitalDoctors(){
        $hospital = Hospital::find(1);   // Hospital::where('id',1)->first();  // Hospital::first();
        //return $hospital -> doctors;

        $hospital = Hospital::with('doctors')->find(1);
        //return $hospital->name;

//        $doctors = $hospital->doctors;
//        foreach ($doctors as $doctor){
//            echo $doctor -> name . '<br>';
//        }

        // $doctor = Doctor::find(3)->hospital->name;

        return $doctor;
    }

}
