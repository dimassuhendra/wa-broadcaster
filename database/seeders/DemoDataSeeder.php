<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\CampaignRecipient;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\MessageTemplate;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $groups = ContactGroup::factory()->count(4)->create();
        $templates = MessageTemplate::factory()->count(4)->create();

        $contacts = Contact::factory()->count(36)->create()
            ->concat(Contact::factory()->count(4)->optedOut()->create());

        $contacts->each(function (Contact $contact) use ($groups): void {
            $contact->groups()->attach(
                $groups->random(fake()->numberBetween(1, 2))->modelKeys(),
            );
        });

        $campaigns = collect();

        foreach (range(1, 8) as $campaignNumber) {
            $campaignFactory = match (true) {
                $campaignNumber <= 4 => Campaign::factory()->sent(),
                $campaignNumber <= 6 => Campaign::factory()->scheduled(),
                default => Campaign::factory(),
            };

            $campaigns->push($campaignFactory->create([
                'message_template_id' => $templates->random()->id,
            ]));
        }

        $eligibleContacts = Contact::whatsappOptedIn()->get();

        foreach ($campaigns->whereIn('status', ['sent', 'scheduled']) as $campaign) {
            foreach ($eligibleContacts->random(fake()->numberBetween(5, 12)) as $contact) {
                $recipientFactory = CampaignRecipient::factory()
                    ->for($campaign)
                    ->for($contact);

                if ($campaign->status === 'scheduled') {
                    $recipientFactory->create();

                    continue;
                }

                $recipientFactory = match (fake()->randomElement(['sent', 'delivered', 'read', 'replied', 'failed'])) {
                    'sent' => $recipientFactory->sent(),
                    'delivered' => $recipientFactory->delivered(),
                    'read' => $recipientFactory->read(),
                    'replied' => $recipientFactory->replied(),
                    'failed' => $recipientFactory->failed(),
                };

                $recipientFactory->create();
            }
        }
    }
}
