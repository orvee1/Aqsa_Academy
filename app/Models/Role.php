<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // auto slug if empty
    protected static function booted(): void
    {
        static::saving(function (Role $role) {
            $role->name = trim($role->name);

            if (blank($role->slug)) {
                $role->slug = Str::slug($role->name);
            } else {
                $role->slug = Str::slug($role->slug);
            }
        });
    }

    public function scopeActive($q)
    {
        return $q->where('status', true);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
            ->withTimestamps();
    }
}
