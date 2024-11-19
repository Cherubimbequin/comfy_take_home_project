<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PolicyManager extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'policy_number',
        'policy_type_id',
        'status',
        'premium_amount',
        'start_date',
        'end_date',
        'next_of_kin',
    ];

    public function policyType()
    {
        return $this->belongsTo(PolicyType::class, 'policy_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

}
