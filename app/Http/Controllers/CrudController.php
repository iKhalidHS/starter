<?php

namespace App\Http\Controllers;

use App\Events\VideoViewer;
use App\Http\Requests\OfferRequest;
use App\Models\Offer;
use App\Models\Video;
use App\Scopes\OfferScope;
use App\Traits\OfferTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use LaravelLocalization;

class CrudController extends Controller
{
    use OfferTrait;
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


    public function store(OfferRequest $request) //Request $request because the data will be received through request , so called the request class
    {

        //validator is method of a class "Request" if we added it above instead of OfferRequest
        // make function takes 3 parameters arrays, 1. inputs 2.validation rule 3.messages to return in case of error
        // [$request->all()] is to bring all the inputs and [all()] converts them to array
        // if we kept the message array blank it will return the default, if we want to rename it , check the case below

//        $rules     = $this->getRules();
//        $messages  = $this->getMessages();
//        $validator = Validator::make($request->all(),$rules,$messages);
//        if($validator->fails()){
//            //return $validator->errors()->first();  //[ ->first() ] to show only the first error
//            return redirect()->back()->withErrors($validator)->withInputs($request->all());
//        }

        //inserting

        //1. save the image
        $file_name = $this->saveImage($request->photo , 'images/offers'); // passing the image and the folder to be saved in

        //2. insert the data
        Offer::create([
            'name_ar'   => $request->name_ar,
            'name_en'   => $request->name_en,
            'photo'     => $file_name,
            'price'     => $request->price,
            'details_ar'=> $request->details_ar,
            'details_en'=> $request->details_en,
        ]);

        return redirect()->back()->with(['success'=>'تم اضافة العرض بنجاح']);
    }


//    protected  function getMessages(){
//        return $messages = [
//            'name.required' =>__('messages.offer name required'),
//            'name.unique'   => 'اسم العرض موجود',
//            'price.numeric' => 'السعر لابد ان يكون رقم',
//        ];
//    }

//    protected function getRules(){
//        return $rules = [
//            'name'    => 'required|max:100|unique:offers,name', //unique name column in offer table
//            'price'   => 'required|numeric',
//            'details' => 'required',
//        ];
//    }


    public function getAllOffers()
    {
//        $offers = Offer::select(
//            'id',
//            'name_'.LaravelLocalization::getCurrentLocale().' as name',
//            'price',
//            'photo', // added by me
//            'details_'.LaravelLocalization::getCurrentLocale().' as details') -> get(); //return collection
//
//        return view('offers.all', compact('offers'));

        ##################### paginate result #######

        $offers = Offer::select(
        'id',
        'name_'.LaravelLocalization::getCurrentLocale().' as name',
        'price',
        'photo', // added by me
        'details_'.LaravelLocalization::getCurrentLocale().' as details'
        ) -> paginate(PAGINATION_COUNT); //return collection

        return view('offers.paginations', compact('offers'));
    }

    public function editOffer($offer_id)
    {
        //Offer::findOrFail($offer_id); // to select from database based on the id but if the id is not there it will return page 404
        $offer = Offer::find($offer_id);  // to search in ag given table by id filed only ( not possible to select by name foe ex. only id)
        if(!$offer)
            return redirect()->back();

        $offer = Offer::select('id','name_ar','name_en','details_ar','details_en','price') -> find($offer_id);
        return view('offers.edit',compact('offer'));
    }


    public function delete($offer_id){
        // check if offer id exists
        $offer = Offer::find($offer_id);  //Offer::where('id','$offer_id')->first();
        if(!$offer_id)
            return redirect()->back()->with(['error'=>__('messages.offer not exist')]);

        $offer ->delete();
        return redirect()->route('offers.all')->with(['success'=>__('messages.offer deleted successfully')]);
    }

    public function updateOffer(OfferRequest $request, $offer_id){ //Note:we make validation and we received the offer id submitted with the route of edit form
        //1. validation

        //2. check if offer exists
        $offer = Offer::find($offer_id);
        if(!$offer)
            return redirect()->back();

        //3. update data

        //you can update the whole record with the new data using [ $request->all() ]
        $offer->update($request->all());   // all() : to make the request as array
        // or you can update specific fields as below :
//        $offer->update([
//            'name_ar'=> $request->name_ar,
//            'name_en'=> $request->name_en,
//            'price'  => $request->price,
//        ]);
        // Note we use update() if we are updating the model using collection , but if we are updating it using model field directly , we use $model-> save() , same like what we did with the listener page

        return redirect()->back()->with(['success'=>'تم التحديث بنجاح']);
    }



    public function getVideo(){
        $video = Video::first(); // this will se;ect the first video in the table
        //$video = Video::select('viewers')->first(); // FROM ME : this will select only the field viewers only in th first video in the table
        event(new VideoViewer($video)); //firing the event using the built-in function event, and passing the table model in it because the event constructor takes that value ! remember the even class.
        return view('video') ->with('video',$video); //passing the model $video in variable video

        //Note: this event example was to learn how to create event & listener , but we can easily update the views count directly here.
    }

    public function getAllInactiveOffers(){

        //where whereNull whereNotNull whereIn

        //return $inactiveOffers = Offer::inactive()->get();
        //return $inactiveOffers = Offer::invalid()->get();

        //return $inactiveOffers = Offer::get(); // we applied a global scope

        // removing global scope //

        return $inactiveOffers = Offer::withoutGlobalScope(OfferScope::class)->get(); //without applying the global scope
    }
}
