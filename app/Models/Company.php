<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'folder_path'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function documents() {
        return $this->hasMany(Document::class);
    }

    // Local scope
    public function scopeCompanyOnly($query)
    {
        // Only filter if the user has a company_id
        if (Auth::check() && Auth::user()->company_id) {
            return $query->where('id', Auth::user()->company_id);
        }

        // If no company_id, return all
        return $query;
    }
}
