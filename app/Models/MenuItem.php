<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id','parent_id',
        'label_bn','label_en',
        'type','url','page_id','post_category_id','route_name',
        'position','open_new_tab','status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'open_new_tab' => 'boolean',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('position');
    }

    public function scopeActive($q)
    {
        return $q->where('status', true);
    }
}
