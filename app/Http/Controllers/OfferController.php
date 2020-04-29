<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfferRequest;
use App\Models\Offer;
use App\Traits\OfferTrait;
use Illuminate\Http\Request;
use LaravelLocalization;

class OfferController extends Controller
{

    use OfferTrait;

    //form to add
    public function create()
    {
        return view('ajaxoffers.create');
    }


    //Save into DB using Ajax
    public function store(OfferRequest $request)
    {
        //inserting

        //1. save the image
        $file_name = $this->saveImage($request->photo, 'images/offers'); // passing the image and the folder to be saved in

        //2. insert the data in db
        $offer = Offer::create([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'photo' => $file_name,
            'price' => $request->price,
            'details_ar' => $request->details_ar,
            'details_en' => $request->details_en,
        ]);

        if ($offer)
            return response()->json([  //will return back the result to the calling method
                'status' => true,
                'msg' => 'تم الحفظ بنجاح'
            ]);

        else
            return response()->json([  //will return back the result to the calling method
                'status' => true,
                'msg' => 'يرجى المحاولة مجددا '
            ]);
    }

    //showing all offers
    public function all()
    {
        $offers = Offer::select(
            'id',
            'name_' . LaravelLocalization::getCurrentLocale() . ' as name',
            'price',
            'photo', // added by me
            'details_' . LaravelLocalization::getCurrentLocale() . ' as details')->limit(10)->get(); //return array of collection "all in db" but first() get only first row
        return view('ajaxoffers.all', compact('offers'));
    }


    //deleting offer
    public function delete(Request $request){//because we are using ajax it will take "Request" so ewe can get the 'data' submitted with the ajax
        // check if offer id exists
        $offer = Offer::find($request->id);  //Offer::where('id','$offer_id')->first();
        if(!$offer)
            return response()->json([  //will return back the result to the calling method
                'status' => false,
                'msg' => 'هذا العرض غير موجود'
            ]);

        $offer ->delete();
        return response()->json([  //will return back the result //with ajax only json return accepted
            'status' => true,
            'msg' => 'تم الحذف بنجاح ',
            'id' => $request->id  //we sent the id to receive it there and remove the row in front of us once removed from db

        ]);
    }

    //edit offer
    public function edit(Request $request) //when using ajax, any passing will be throw Request (means the data in the ajax method)
    {
        //Offer::findOrFail($offer_id); // to select from database based on the id but if the id is not there it will return page 404
        $offer = Offer::find($request -> offer_id);  // to search in ag given table by id filed only ( not possible to select by name foe ex. only id)
        if(!$offer)
            return response()->json([  //will return back the result to the calling method
                'status' => false,
                'msg' => 'هذا العرض غير موجود'
            ]);

        $offer = Offer::select('id','name_ar','name_en','details_ar','details_en','price') -> find($request -> offer_id);
        return view('ajaxoffers.edit',compact('offer'));
    }

    public function update(Request $request){

        $offer = Offer::find($request->offer_id);
        if(!$offer)
            return response()->json([  //will return back the result to the calling method
                'status' => false,
                'msg' => 'هذا العرض غير موجود'
            ]);

        $offer->update($request->all());
        return response()->json([  //will return back the result
            'status' => true,
            'msg' => 'تم التحديث بنجاح'
        ]);
    }

}
