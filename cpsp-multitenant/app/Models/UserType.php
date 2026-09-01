<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserType extends Model
{
    use BelongsToTenant;

    protected $fillable = ['tenant_id', 'name'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
