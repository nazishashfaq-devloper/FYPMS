<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'sender_id',
        'receiver_id',
        'status'
    ];

    // TEAM relation
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // SENDER relation
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // RECEIVER relation
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}