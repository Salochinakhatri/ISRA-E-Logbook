<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TraineeDissertation extends Model
{
    use BelongsToTenant;

    protected $table = 'trainee_dissertations';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'dissertation_title',
        'application_date',
        'status',
        'status_date',
        'remarks',
    ];

    protected $casts = [
        'application_date' => 'date',
        'status_date'      => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
