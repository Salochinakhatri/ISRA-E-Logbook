<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RotationalEntry extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'user_id', 'form_type', 'hospt_reg_no',
        'date_of_admission', 'pt_gender', 'pt_age', 'pt_age_type',
        'pt_diagnosis', 'under_sup_name', 'level_id', 'outcome_id',
        'brief_desc', 'entry_for_prog_id', 'program',
        'rot_ids', 'rot_detail_ids', 'alt_procedure', 'std_post',
        'entry_status', 'supervisor_remarks', 'approved_at', 'approved_by',
    ];

    protected $casts = [
        'rot_ids'        => 'array',
        'rot_detail_ids' => 'array',
        'date_of_admission' => 'date',
        'approved_at'    => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
