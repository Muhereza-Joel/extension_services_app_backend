<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['farmer_id', 'officer_id'];

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
