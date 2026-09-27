<?php

namespace App\Models;

use Database\Factories\CampaignRecipientFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'campaign_id',
    'contact_id',
    'status',
    'sent_at',
    'delivered_at',
    'read_at',
    'replied_at',
    'failed_at',
    'error_message',
])]
class CampaignRecipient extends Model
{
    /** @use HasFactory<CampaignRecipientFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'delivered_at' => 'datetime',
            'read_at' => 'datetime',
            'replied_at' => 'datetime',
            'failed_at' => 'datetime',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
