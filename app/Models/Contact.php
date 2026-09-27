<?php

namespace App\Models;

use Database\Factories\ContactFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'phone',
    'email',
    'company',
    'notes',
    'is_whatsapp_opt_in',
    'consented_at',
    'opted_out_at',
    'opt_in_source',
])]
class Contact extends Model
{
    /** @use HasFactory<ContactFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_whatsapp_opt_in' => 'boolean',
            'consented_at' => 'datetime',
            'opted_out_at' => 'datetime',
        ];
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(ContactGroup::class)->withTimestamps();
    }

    public function campaignRecipients(): HasMany
    {
        return $this->hasMany(CampaignRecipient::class);
    }

    public function scopeWhatsappOptedIn(Builder $query): Builder
    {
        return $query
            ->where('is_whatsapp_opt_in', true)
            ->whereNotNull('consented_at')
            ->whereNull('opted_out_at');
    }
}
