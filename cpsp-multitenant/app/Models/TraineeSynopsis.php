<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TraineeSynopsis extends Model
{
    use BelongsToTenant;

    protected $table = 'trainee_synopses';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'synopsis_name',
        'status',
        'status_date',
        'remarks',
    ];

    protected $casts = [
        'status_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
