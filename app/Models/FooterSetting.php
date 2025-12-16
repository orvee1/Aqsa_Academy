<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FooterSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'about','address','phone','email','map_embed','copyright_text',
    ];
}
