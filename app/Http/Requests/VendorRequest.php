<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
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
            'logo' => 'required_without:id|mimes:jpg,jpeg,png,ico',
            'name' => 'required|string|max:100',
            'password' => 'required_without:id',
            'mobile' => 'required|max:100|unique:vendors,mobile,'.$this -> id,
            'email' => 'required|email|unique:vendors,email,'.$this -> id,
            'address' => 'required|string|max:500',
            'category_id' => 'required|exists:main_categories,id',

        ];
    }

    public function messages()
    {
        return [
            'required' => 'هذا الحقل مطلوب',
            'logo.required_without' => 'الصورة مطلوبة',
            'max' => 'هذا الحقل طويل',
            'category_id.exists' => 'هذا القسم غير موجود',
            'email.email ' => 'صيغة البريد الالكترونى غير صحيحة',
            'email.unique ' => 'صيغة البريد الالكترونى مستخدم من قبل',
            'mobile.unique ' => 'رقم الهاتف مستخدم من قبل',
            'name.string ' => 'الاسم يجب ان يكون حروف وارقام',

        ];

    }
}
