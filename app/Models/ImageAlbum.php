<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageAlbum extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'status'];

    protected $casts = ['status' => 'boolean'];

    public function items()
    {
        return $this->hasMany(ImageItem::class, 'album_id')->orderBy('position');
    }

    public function scopeActive($q)
    {
        return $q->where('status', true);
    }
}
