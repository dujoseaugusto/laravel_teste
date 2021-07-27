<?php

namespace App\Http\Livewire\Contacts;

use App\Models\Contact;
use Livewire\Component;

class ContactNew extends Component
{
    public Contact $newContact;

    public function mount(Contact $contact){
        $this->newContact = $contact;
    }

    public function store(){
        $this->validate();
        $this->newContact->save();
        $this->newContact = new Contact();
        $this->emit('created');
    }
    public function render()
    {
       return view('contacts.contact-new');
    }

    protected function rules(){
        return [
            'newContact.name' => 'required|string',
            'newContact.email' => 'required|email',
            'newContact.phone' => 'required',
            'newContact.message' => 'required|min:10',
        ];
    }
}
