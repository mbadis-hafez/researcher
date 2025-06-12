<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name'=>'required',
            'last_name'=>'required',
            'image'=>'nullable|image',
            'organisation_record'=>'nullable',
            'organisation'=>'nullable',
            'email'=>'nullable|email',
            'phone_number'=>'nullable|phone',
            'addional_phone_number'=>'nullable|phone',
            'website'=>'nullable',
            'addional_website'=>'nullable',
            'country'=>'nullable',
            'city'=>'nullable',
            'street'=>'nullable',
            'zip_code'=>'nullable',
            'gender'=>'nullable',
            'spoken_languages'=>'nullable',
            'date_of_birth'=>'nullable',
            'status'=>'nullable',
        ];
    }
}