<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBusinessClientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string|min:2',
            'email' => 'required|email|unique:business_clients,email',
            'job' => 'required|string|min:2',
            'phone' => 'required|string|min:6',
            'business_type' => 'required|string',
            'organization' => 'required|string',

            'other_business_type' => 'required_if:business_type,other',
            'terms' => 'accepted'
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Please enter your full name',
            'email.unique' => 'This email is already registered',
            'terms.accepted' => 'You must accept the terms and conditions'
        ];
    }
}
