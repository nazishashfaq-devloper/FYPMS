<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'team_id',
        'uploaded_by',
        'document_type',
        'file_path',
        'original_name',
        'status',
        'feedback',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}