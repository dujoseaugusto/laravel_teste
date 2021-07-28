<?php

namespace Tests\Feature\Contacts;

use App\Http\Livewire\Contacts\ContactItem;
use App\Http\Livewire\Contacts\ContactList;
use App\Http\Livewire\Contacts\ContactNew;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * @group contacts
 * @group contactTest
 */
class ContactTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function canCreateContact()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $contactFake = Contact::factory()->make();

        Livewire::test(ContactNew::class)
            ->call('mountNew',$contactFake)
            ->call('store')
            ->assertEmitted('created')
            ->assertEmitted('refreshList');

        $this->assertDatabaseHas('contacts', $contactFake->toArray());
    }

    /**
     * @test
     */
    public function checkRequiredFieldsCreateContact()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        Livewire::test(ContactNew::class)
            ->call('store')
            ->assertHasErrors([
                'newContact.name' => 'required',
                'newContact.email' => 'required',
                'newContact.phone' => 'required',
                'newContact.message' => 'required',
            ]);
    }

    /**
     * @test
     */
    public function cannotCreateInvalidEmailContact()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $contactFake = Contact::factory()
            ->make([
                'email' => 'invalid',
                'team_id' => $user->currentTeam->id,
            ]);

        Livewire::test(ContactNew::class)
            ->call('mountNew', $contactFake)
            ->call('store')
            ->assertHasErrors(['newContact.email' => 'email']);;
    }

    /**
     * @test
     */
    public function canDisplayPaginateContact()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        Contact::factory(30)->create();

        Livewire::withQueryParams(['page' => 2])
                ->test(ContactList::class)
                ->assertPayloadSet('page', 2);;
    }

      /**
     * @test
     */
    public function canUpdateContact()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $contact = Contact::factory()->create();

        $contact->name = 'update name';
        $contact->email = 'update@email.com';

        Livewire::test(ContactItem::class)
            ->call('edit', $contact)
            ->call('update')
            ->assertEmitted('refreshList');

        $this->assertDatabaseHas('contacts', [
            'name' => 'update name',
            'email' => 'update@email.com',
        ]);
    }

    /**
     * @test
     */
    public function canDestroyContact()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $contact = Contact::factory()->create();

        Livewire::test(ContactItem::class)
            ->call('confirmDeletion', $contact)
            ->call('destroy')
            ->assertEmitted('refreshList');

        $this->assertDatabaseMissing('contacts', $contact->toArray());
    }
}