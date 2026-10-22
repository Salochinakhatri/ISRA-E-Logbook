<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TraineeWorkshop extends Model
{
    use BelongsToTenant;

    protected $table = 'trainee_workshops';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'workshop_name',
        'time_duration',
        'status',
        'status_date',
    ];

    protected $casts = [
        'status_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
