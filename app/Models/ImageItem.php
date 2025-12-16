<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'album_id', 'title', 'image_path', 'caption',
        'position', 'status',
    ];

    protected $casts = ['status' => 'boolean'];

    public function album()
    {
        return $this->belongsTo(ImageAlbum::class, 'album_id');
    }
}
