<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcelDocuments extends Model
{
    protected $fillable = [
        'user_id',
        'search_field',
        'document_name',
        'document_directory',
        'uploaded_at',
    ];
}
