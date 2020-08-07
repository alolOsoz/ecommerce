<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
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
            'name'=>'required|string|max:100',
            'abbr'=>'required|string|max:10',
            'direction'=>'required|in:rtl,ltr',
        //    'active'=>'required|in:0,1',

        ];
    }
    public function messages()
    {
        return [
            'required'=>'هذا الحقل مطلوب',
            'name.string'=>'اسم اللغة لابد ان يكون احرف',
            'abbr.string'=>'اختصار اللغة لابد ان يكون احرف',
            'name.max'=>'اسم اللغة لا يتعدى 100 حرف',
            'abbr.max'=>'اختصار اللغة لا يتعدى 10 حرف',
            'in'=>'القيم المدخلة غير صحيحة',

        ];

    }
}
