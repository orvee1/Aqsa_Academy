<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'status'];

    protected $casts = ['status' => 'boolean'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function scopeActive($q)
    {
        return $q->where('status', true);
    }
}
