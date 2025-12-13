<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name','slug','domain','status'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function institute()
    {
        return $this->hasOne(Institute::class);
    }
}
