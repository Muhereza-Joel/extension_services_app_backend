<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'phone_number',
        'nin',
        'date_of_birth',
        'gender',
        'country',
        'district',
        'village',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
