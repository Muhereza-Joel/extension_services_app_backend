<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'meeting_id',
        'price',
        'status',
        'attendee_id',
    ];

    protected static function booted()
    {
        static::creating(function ($ticket) {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = 'TCKT-' . strtoupper(Str::random(10)); // Generates a unique ticket number
            }
        });
    }

    /**
     * Get the meeting associated with the ticket.
     */
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    /**
     * Get the attendee for the ticket (if applicable).
     */
    public function attendee()
    {
        return $this->belongsTo(User::class, 'attendee_id');
    }
}
