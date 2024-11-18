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
    ];

    public function policy()
    {
        return $this->belongsTo(PolicyManager::class);
    }

}
