<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalEntry extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'user_id', 'date_of_diss', 'fac_by',
        'ref_of_art_disc', 'topic', 'ref_article',
        'program', 'std_post', 'entry_status', 'supervisor_remarks', 'approved_at', 'approved_by',
    ];

    protected $casts = [
        'date_of_diss' => 'date',
        'approved_at'  => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
