<?php

namespace App\Models\BarangayElectrician;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ElectricianEducationalBackground extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;


    protected $table = 'barangay_electrician_educational_backgrounds';

    public function electrician()
    {
        return $this->belongsTo('App\Models\Electrician',  'electrician_id', 'id');
    }

    protected $fillable = [
        'electrician_id',
        'educational_stage',
        'name_of_school',
        'degree_recieved',
        'year_graduated'
    ];
}
