<?php

namespace App\Http\Livewire\Contacts;

use App\Models\Contact;
use Livewire\Component;

class ContactItem extends Component
{
    public Contact $contact;
    public bool $updating;
    public bool $destroying;

    public function edit(Contact $contact){
        $this->updating = true;
        $this->contact = $contact;
    }

    public function update()
    {
        $this->validate();
        $this->contact->save();
        $this->updating = false;
        $this->emit('refreshList');
    }
    
    public function confirmDeletion(Contact $contact)
    {
        $this->destroying = true;
        $this->contact = $contact;
    }

    public function destroy()
    {
        $this->contact->delete();
        $this->destroying = false;
        $this->emit('refreshList');
    }

    public function render()
    {
        return view('contacts.contact-item');
    }

    protected function rules(){
        return [
            'contact.name' => 'required|string',
            'contact.email' => 'required|email',
            'contact.phone' => 'required',
            'contact.message' => 'required|min:10',
        ];
    }
}
