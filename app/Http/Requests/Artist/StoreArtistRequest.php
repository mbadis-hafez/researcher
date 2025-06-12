<?php

namespace App\Http\Requests\Artist;

use Illuminate\Foundation\Http\FormRequest;

class StoreArtistRequest extends FormRequest
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
            'name' => 'required|min:3|unique:artists',	
            'email'	=> 'nullable|email',
            // 'password'	=>'nullable|min:6|alpha_num',
            'password'	=>'nullable',
            'description'=>'nullable',
            'artist_date'=>'nullable',	
            'short_biography'=>'nullable',
            'full_biography'=>'nullable',	
            'website'	=>'nullable',
            'notes'	=>'nullable',
            'country'	=>'nullable',
            'image'=>'nullable|image',
            'images'=>'nullable',
        ];
    }
}