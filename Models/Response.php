<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = ['rfq_id'];

    // Each response belongs to an RFQ
    public function rfq()
    {
        return $this->belongsTo(RFQ::class);
    }
}
