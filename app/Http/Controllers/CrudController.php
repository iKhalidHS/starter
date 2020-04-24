<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CrudController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function getOffers()
    {
        return Offer::select('id','name')->get(); // [get()] alone is selecting all column , [ select('id','name')->get() ] to [get()] to select only id and name
    }

//    public function store()
//    {
//        //use create to insert, note it's parameters are associative array
//        Offer::create([
//            'name' =>'offer3',
//            'price' => '5000',
//            'details'=> 'offer details',
//        ]);
//    }

    public function create()
    {
        return view('offers.create');
    }

    public function store(Request $request) //Request $request because the data will be received through request , so called the request class
    {

        //validator
        // make function takes 3 parameters arrays, 1. inputs 2.validation rule 3.messages to return in case of error
        // [$request->all()] is to bring all the inputs and [all()] converts them to array
        // if we kept the message array blank it will return the default, if we want to rename it , check the case below

        $rules     = $this->getRules();
        $messages  = $this->getMessages();
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            //return $validator->errors()->first();  //[ ->first() ] to show only the first error
            return redirect()->back()->withErrors($validator)->withInputs($request->all());
        }

        //inserting
        Offer::create([
            'name' => $request->name,
            'price' => $request->price,
            'details' => $request->details,
        ]);

        return redirect()->back()->with(['success'=>'تم اضافة العرض بنجاح']);
    }

    protected  function getMessages(){
        return $messages = [
            'name.required' =>__('messages.offer name required'),
            'name.unique'   => 'اسم العرض موجود',
            'price.numeric' => 'السعر لابد ان يكون رقم',
        ];
    }

    protected function getRules(){
        return $rules = [
            'name'    => 'required|max:100|unique:offers,name', //unique name column in offer table
            'price'   => 'required|numeric',
            'details' => 'required',
        ];
    }

}
