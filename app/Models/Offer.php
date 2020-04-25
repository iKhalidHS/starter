<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = "offers"; // you can change the table names other than model name like it can be "myOffers"
    protected $fillable =['name_ar','name_en','photo','price','details_ar','details_en','created_at','updated_at']; // these column only will accept update
    protected $hidden =['created_at','updated_at']; //even if we select * it will not return the hidden column
    //public $timestamps = false; // to avoid updating the 'created_at' and 'updated_at' columns
}
