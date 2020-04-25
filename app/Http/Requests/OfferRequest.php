<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_ar'    => 'required|max:100|unique:offers,name_ar', //unique name column in offer table
            'name_en'    => 'required|max:100|unique:offers,name_en', //unique name column in offer table
            'price'   => 'required|numeric',
            'details_ar' => 'required',
            'details_en' => 'required',
        ];
    }

    public function messages(){
        return [
            'name.required' =>__('messages.offer name required'),
            'name.unique'   => 'اسم العرض موجود',
            'price.numeric' => 'السعر لابد ان يكون رقم',
        ];
    }
}
