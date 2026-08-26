<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChangeMeterLeadContractor extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'change_meter_lead_contractors';

    protected $fillable = [
        'contractor_team_leader_full_name',
        'area',
        'municipality',
        'signature_path',
    ];
}
