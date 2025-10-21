<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcelDocuments extends Model
{
    protected $fillable = [
        'user_id',
        'document_id',
        'search_field',
        'document_name',
        'document_directory',
        'uploaded_at',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
