<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deadline extends Model
{
    protected $fillable = [
        'title',
        'document_type',
        'deadline_date',
        'description',
    ];

    protected $casts = [
        'deadline_date' => 'date',
    ];

    /**
     * A NULL document_type means this deadline is a general deadline that
     * applies to any document type without its own specific deadline.
     */
    public function isGeneral()
    {
        return is_null($this->document_type);
    }
}