<?php

namespace App\Http\Livewire\Contacts;

use App\Models\Contact;
use Livewire\Component;

class ContactItem extends Component
{
    public Contact $contact;
    public bool $updating;
    public bool $destroying;
    
    public function render()
    {
        return view('contacts.contact-item');
    }
}
