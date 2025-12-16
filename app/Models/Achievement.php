<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','description','year',
        'image_path','position','status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
