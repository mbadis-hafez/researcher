<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['organisation_record','first_name','last_name','image','position','organisation','importance',
        'email','phone_number','addional_phone_number','website','addional_website','address','gender','spoken_languages',
        'date_of_birth','max_budget','consultant','secondary_consultant','contact_visibility','mailing_settings','status',
        'country','city','street','zip_code'];

    protected $appends = ['image_path','full_name'];

    public function getImagePathAttribute():string
    {
        return asset('storage/contact/'.$this->image);
    }

    public function getFullNameAttribute():string
    {
        return $this->first_name.' '.$this->last_name;
    }
}