<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TournamentData extends BaseModel
{

    protected $table = 'tournaments_data';

    protected $fillable = [
        'tournament_id',
        'student_id',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class, 'tournament_id');
    }
}
