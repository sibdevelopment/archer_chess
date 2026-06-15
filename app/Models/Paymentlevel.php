<?php

namespace App\Models;

use App\Models\Level;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paymentlevel extends BaseModel
{
    protected $fillable = ['id', 'name', 'level_id', 'sequence', 'usa_fees', 'canada_fees', 'australia_fees', 'newzealand_fees', 'india_fees', 'uae_fees', 'uk_fees', 'status', 'qatar_fees', 'singapore_fees', 'european_union_fees', 'oman_fees'];

    protected $table = 'paymentlevels';

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id', 'id');
    }
}
