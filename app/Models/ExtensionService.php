<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ExtensionService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'color',
        'status',
        'created_by',
        'assigned_worker_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            if (Auth::check()) {
                $service->created_by = Auth::id();
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedWorker()
    {
        return $this->belongsTo(User::class, 'assigned_worker_id');
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }
}
