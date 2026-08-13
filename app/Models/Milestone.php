<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $fillable = [
        'team_id',
        'title',
        'status',
        'due_date',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}