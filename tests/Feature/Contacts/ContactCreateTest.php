<?php

namespace Tests\Feature\Contacts;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * @group concats
 * @group concatsCreate
 */
class ContactCreateTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * @test
     */
    public function canCreateContact(){
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        
        $contactFake = Contact::factry()->make();

        Livewire::test(ContactNew::class)
            ->call('mount',$contactFake)
            ->call('store')
            ->assertEmitted('created');
        
        $this->assertDatabaseHas('contacts',$contactFake->toArray());
    }
    
}
