<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name','location','status'];

    protected $casts = ['status' => 'boolean'];

    public function items()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function rootItems()
    {
        return $this->items()->whereNull('parent_id')->orderBy('position');
    }

    public function scopeActive($q)
    {
        return $q->where('status', true);
    }
}
