<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'team_name',
        'leader_id',
        'supervisor_id'
    ];

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }

    public function latestProposal()
    {
        return $this->hasOne(Proposal::class)->latestOfMany();
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function presentations()
    {
        return $this->hasMany(Presentation::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}