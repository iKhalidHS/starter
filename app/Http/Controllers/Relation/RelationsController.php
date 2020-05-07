<?php

namespace App\Http\Controllers\Relation;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\Phone;
use App\Models\Service;
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

        // return $doctor;
    }


    public function hospitals(){
        $hospitals = Hospital::select('id','name','address')->get();
        return view('doctors.hospitals', compact('hospitals'));
    }

    public function doctors($hospital_id){

        // make validation that the id is exist then continue any of the following

        #1
        $hospital = Hospital::find($hospital_id);
        $doctors = $hospital->doctors;
        return view('doctors.doctors', compact('doctors'));

        #2
        //return $hospital = Hospital::find($hospital_id)->doctors;

    }

    // get all hospital which must have doctors
    public function hospitalsHasDoctor(){
        $hospitals = Hospital::whereHas('doctors')->get();
        return $hospitals;
    }

    public function hospitalsHasOnlyMaleDoctors(){
        $hospitals = Hospital::with('doctors')->whereHas('doctors',function($q){
            $q->where('gender',1);
        })->get();

        return $hospitals;
    }

    public function hospitals_not_has_doctors(){
        return Hospital::whereDoesntHave('doctors')->get();

    }

    public function deleteHospital($hospital_id){
        $hospital = Hospital::find($hospital_id);    //you can use findOrFails()
        if(!$hospital)
            return abort('404'); //if not found , it will go to page 404 'notfound'
        // #1 delete doctors in this hospital
        $hospital -> doctors()-> delete(); // note we called the relation [ doctors() ] as a function not only [ 'doctors' ]
        // #2 delete the hospital itself
        $hospital -> delete();

        return redirect()->back();
        // return redirect()-> route('hospital.all');
    }

    public function getDoctorServices(){
        $doctor = Doctor::with('services')->find(5);
        //after making check, do the following
        return $doctor -> services;
    }

    public function getServiceDoctors(){
        return $doctors = Service::with(['doctors' => function($q){
            $q->select('doctors.id','name','title'); //note we specified the id of table doctor to avoid error
        }])->find(1);
    }

    public function getDoctorServicesById($doctorId){
        $doctor = Doctor::find($doctorId);
        $services = $doctor -> services; //selected doctor services

        $doctors  = Doctor::select('id','name')->get();
        $allServices = Service::select('id','name')->get(); //all database services

        return view('doctors.services',compact('services','doctors', 'allServices'));
    }

    public function saveServicesToDoctors(Request $request){
        $doctor = Doctor::find($request->doctor_id);
        if(!$doctor)
            return abort('404');
        //$doctor->services()->attach($request->servicesIds); //many to many insert to db // attach is a method to save array of ... regardless of repeated or not // services() is the relation with doctor
        //$doctor->services()->sync($request->servicesIds); //sync() remove all the old related to that doctor and update it with new selection only !
        $doctor->services()->syncWithoutDetaching($request->servicesIds); //will add only the new selectios to the old ones in db
        return redirect()->back();

    }


    public function getPatientDoctor(){
        $patient =  Patient::find(2);
        return $patient -> doctor;
    }

    public function getCountryDoctor(){
        $country = Country::find(1);
        return $country -> doctors;
    }

}
