<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Statement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','body',
        'author_name','author_designation','author_photo_path',
        'position','status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
