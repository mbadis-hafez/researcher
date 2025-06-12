<?php

namespace App\Interfaces;

use App\Models\Contact;

interface ContactRepositoryInterface
{

    public function getContacts();
    public function getAllContacts();
    public function getContactById($id): Contact;
    public function deleteContact($id);
    public function createContact(array $data): Contact;
    public function updateContact($id, array $data);
    public function bulkInsert(array $data): bool;
}