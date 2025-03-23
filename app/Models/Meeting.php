<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'venue',
        'presenter',
        'date',
        'time',
        'capacity',
        'status',
        'extension_service_id'
    ];


    public function extensionService()
    {
        return $this->belongsTo(ExtensionService::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
