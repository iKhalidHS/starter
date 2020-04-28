<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfferRequest;
use App\Models\Offer;
use App\Traits\OfferTrait;
use Illuminate\Http\Request;

class OfferController extends Controller
{

    use OfferTrait;

    //form to add
    public function create(){
        return view('ajaxoffers.create');
    }


    //Save into DB using Ajax
    public function store(OfferRequest $request){
        //inserting

        //1. save the image
        $file_name = $this->saveImage($request->photo , 'images/offers'); // passing the image and the folder to be saved in

        //2. insert the data in db
        $offer = Offer::create([
                 'name_ar'   => $request->name_ar,
                 'name_en'   => $request->name_en,
                 'photo'     => $file_name,
                 'price'     => $request->price,
                 'details_ar'=> $request->details_ar,
                 'details_en'=> $request->details_en,
        ]);

        if($offer)
            return response()-> json([  //will return back the result to the calling method
                'status' => true,
                'msg'    => 'تم الحفظ بنجاح'
            ]);

        else
            return response()-> json([  //will return back the result to the calling method
                'status' => true,
                'msg'    => 'يرجى المحاولة مجددا '
            ]);
    }
}

