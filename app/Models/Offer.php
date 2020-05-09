<?php

namespace App\Models;

use App\Scopes\OfferScope;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = "offers"; // you can change the table names other than model name like it can be "myOffers" based on matching with the table in the database
    protected $fillable =['name_ar','name_en','photo','price','details_ar','details_en','created_at','updated_at','status']; // these column only will accept update
    protected $hidden =['created_at','updated_at']; //even if we select * it will not return the hidden column
    //public $timestamps = false; // to avoid updating the 'created_at' and 'updated_at' columns


    //register global scope
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OfferScope); //OfferScope is the class of scope related
    }

    ########## Local scopes #####################
    public function scopeInactive($query)
    {
        return $query-> where('status',0);
    }

    public function scopeInvalid($query)
    {
        return $query-> where('status',0)->wherenull('details_ar');
    }


    //// mutators
    public function setNameEnAttribute($value){  //write in camel case / automatically will understand the _ between Name and En
        $this->attributes['name_en'] = strtoupper($value);
    }
}

