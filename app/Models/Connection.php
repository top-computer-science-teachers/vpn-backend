<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    protected $table = 'connections';

    protected $fillable = [
        'user_id',
        'is_demo',
        'is_active',
        'type',
        'start_date',
        'end_date',
        'demo_activated',
        'buy_count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
