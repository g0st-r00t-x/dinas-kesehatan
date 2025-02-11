<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'causer_type',
        'causer_id',
        'properties',
        'event'
    ];

    protected $casts = [
        'properties' => 'array'
    ];

    public function subject()
    {
        return $this->morphTo();
    }

    public function causer()
    {
        return $this->morphTo();
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }
}
