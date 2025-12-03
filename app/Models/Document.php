<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Document extends Model
{
    use HasFactory;
    protected $fillable = ['company_id', 'directory', 'filename', 'filepath','search_field','uploaded_at'];

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function scopeCompanyOnly($query)
    {
        // Only filter if the user has a company_id
        if (Auth::check() && Auth::user()->company_id) {
            return $query->where('company_id', Auth::user()->company_id);
        }

        // If no company_id, return all
        return $query;
    }
}
