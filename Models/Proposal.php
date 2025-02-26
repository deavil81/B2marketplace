<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Rfq;
use App\Models\User;
use App\Models\Proposal;

class Proposal extends Model
{
    protected $fillable = [
        'rfq_id',
        'price',
        'lead_time',
        'seller_id',
        'user_id',
        'description',
        'budget',
        'status',
        'selected' => 'Boolean',
        
    ];

    public function rfq()
    {
        return $this->belongsTo(Rfq::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
