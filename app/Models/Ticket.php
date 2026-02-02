<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'title',
        'description',
        'status',
        'priority',
        'opened_at',
        'closed_at',
    ];

    // Casts para asegurar que opened_at y closed_at sean Carbon (datetime)
    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function scopeStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopePriority($query, $priority)
    {
        if ($priority) {
            return $query->where('priority', $priority);
        }
        return $query;
    }
}