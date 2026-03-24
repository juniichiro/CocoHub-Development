<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerDetail extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'age',
        'address',
        'phone_number',
        'profile_picture',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}