<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignRecipient;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\MessageTemplate;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seeder_creates_related_records_and_only_includes_consented_contacts(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(4, ContactGroup::count());
        $this->assertSame(36, Contact::whatsappOptedIn()->count());
        $this->assertSame(40, Contact::count());
        $this->assertSame(4, MessageTemplate::count());
        $this->assertSame(8, Campaign::count());
        $this->assertSame(40, Contact::has('groups')->count());
        $this->assertSame(8, Campaign::whereHas('template')->count());
        $this->assertGreaterThan(0, CampaignRecipient::count());
        $this->assertSame(
            0,
            CampaignRecipient::query()
                ->whereHas('contact', fn ($query) => $query
                    ->where('is_whatsapp_opt_in', false)
                    ->orWhereNull('consented_at')
                    ->orWhereNotNull('opted_out_at'))
                ->count(),
        );
    }

    public function test_campaign_recipients_require_unique_campaign_and_contact_pairs(): void
    {
        $campaign = Campaign::factory()->create();
        $contact = Contact::factory()->create();

        $campaign->recipients()->create(['contact_id' => $contact->id]);

        $this->expectException(QueryException::class);

        $campaign->recipients()->create(['contact_id' => $contact->id]);
    }

    public function test_deleting_a_campaign_removes_its_recipients_and_preserves_its_contacts(): void
    {
        $campaign = Campaign::factory()->create();
        $contact = Contact::factory()->create();
        $recipient = CampaignRecipient::factory()
            ->for($campaign)
            ->for($contact)
            ->create();

        $campaign->delete();

        $this->assertDatabaseMissing('campaign_recipients', ['id' => $recipient->id]);
        $this->assertModelExists($contact);
    }

    public function test_contact_opt_in_factory_state_clears_consent_details(): void
    {
        $contact = Contact::factory()->withoutWhatsAppConsent()->create();
        $optedOutContact = Contact::factory()->optedOut()->create();

        $this->assertFalse($contact->is_whatsapp_opt_in);
        $this->assertNull($contact->consented_at);
        $this->assertNotNull($optedOutContact->opted_out_at);
        $this->assertFalse(Contact::whatsappOptedIn()->exists());
    }
}
