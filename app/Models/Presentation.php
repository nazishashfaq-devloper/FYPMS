<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'scheduled_by',
        'phase',
        'presentation_date',
        'presentation_time',
        'venue',
        'meeting_link',
        'panel_members'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function scheduledBy()
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }
}