<?php

namespace App\Traits;

trait OfferTrait
{
    public function saveImage($photo,$folder){
        $file_extension = $photo-> getClientOriginalExtension(); //to get the uploaded file extension
        $file_name      = time().'.'.$file_extension;
        $path           = $folder;
        $photo -> move($path,$file_name); //to move the uploaded file to the storage

        return $file_name;
    }
}
