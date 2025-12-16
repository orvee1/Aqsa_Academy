<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_title', 'title', 'url', 'position', 'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
