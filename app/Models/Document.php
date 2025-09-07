<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $fillable = ['company_id', 'directory', 'filename', 'filepath'];

    public function company() {
        return $this->belongsTo(Company::class);
    }
}
