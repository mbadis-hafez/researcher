<?php

namespace App\Repositories;

use App\Interfaces\ContactRepositoryInterface;
use App\Models\Contact;
use App\Traits\FileManagerTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ContactRepository implements ContactRepositoryInterface
{
    use FileManagerTrait;
    
    public function __construct(
        private readonly Contact $contact,
    ) {}
    
    public function getContacts()
    {
        return Contact::all();
    }

    public function getAllContacts()
    {
        return Contact::get();
    }
    
    public function getContactById($id): Contact
    {
        return Contact::where('id',$id)->first();
    }

    public function deleteContact($id)
    {
        Contact::findOrFail($id)->delete();
    }

    public function updateContact($id, array $data)
    {
        $contact = $this->getContactById($id);
        $this->storeContact($contact,$data,'edit');
    }

    public function createContact(array $data): Contact
    {
        $contact = new Contact();
        $this->storeContact($contact,$data,'create');
        return $contact;
    }

    private function storeContact($contact,$data,$action)
    {
        $contact->first_name = $data['first_name'];
        $contact->last_name = $data['last_name'];
        $contact->email = $data['email'];
        $contact->organisation_record = $data['organisation_record']=='on' ? 1 : 0;
        $contact->organisation = $data['organisation'];
        $contact->phone_number = $data['phone_number'];
        $contact->addional_phone_number = $data['addional_phone_number'];
        $contact->website = @$data['website'];
        $contact->addional_website = $data['addional_website'];
        $contact->country = $data['country'];
        $contact->city = $data['city'];
        $contact->street = $data['street'];
        $contact->zip_code = $data['zip_code'];
        $contact->gender = $data['gender'];
        $contact->spoken_languages = $data['spoken_languages'];
        $contact->date_of_birth = $data['date_of_birth'];
        // $contact->status = @$data['status'];
        $contact->image = array_key_exists('image',$data) ? $this->upload('contact/', $data['image']->getClientOriginalExtension(), $data['image']) : ($action == 'create' ? null : $contact->image);
        $contact->save();
    }

    public function bulkInsert(array $data): bool
    {
        $this->contact->insert($data);
        
        return true;
    }

}