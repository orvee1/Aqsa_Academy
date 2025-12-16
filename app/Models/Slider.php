<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'subtitle', 'image_path', 'link_url',
        'position', 'status', 'start_at', 'end_at',
    ];

    protected $casts = [
        'status'   => 'boolean',
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    // client-side show করার জন্য helper (optional)
    public function isActiveNow(): bool
    {
        if (! $this->status) {
            return false;
        }

        $now = now();
        if ($this->start_at && $now->lt($this->start_at)) {
            return false;
        }

        if ($this->end_at && $now->gt($this->end_at)) {
            return false;
        }

        return true;
    }
}
