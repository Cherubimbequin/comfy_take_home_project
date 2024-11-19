<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payments extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'policy_id',
        'reference',
        'amount',
        'status',
        'user_id',
        'channel',
        'currency',
        'mobile_money_number',
        'payer_ip_address',
        'paid_at'

    ];

    public function policy()
    {
        return $this->belongsTo(PolicyManager::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
