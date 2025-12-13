<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'slogan',
        'address',
        'eiin',
        'school_code',
        'college_code',
        'phone_1',
        'phone_2',
        'mobile_1',
        'mobile_2',
        'link_1',
        'link_2',
        'link_3',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
