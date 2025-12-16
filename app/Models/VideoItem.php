<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideoItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'youtube_url', 'thumbnail_path',
        'position', 'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Blade এ thumbnail fallback হিসেবে use করতে পারবেন
    public function youtubeId(): ?string
    {
        $url = trim((string) $this->youtube_url);

        // youtu.be/ID
        if (preg_match('~youtu\.be/([A-Za-z0-9_-]{6,})~', $url, $m)) {
            return $m[1];
        }

        // youtube.com/watch?v=ID
        if (preg_match('~v=([A-Za-z0-9_-]{6,})~', $url, $m)) {
            return $m[1];
        }

        // youtube.com/embed/ID
        if (preg_match('~youtube\.com/embed/([A-Za-z0-9_-]{6,})~', $url, $m)) {
            return $m[1];
        }

        return null;
    }

    public function youtubeThumbUrl(): ?string
    {
        $id = $this->youtubeId();
        return $id ? "https://img.youtube.com/vi/{$id}/hqdefault.jpg" : null;
    }
}
