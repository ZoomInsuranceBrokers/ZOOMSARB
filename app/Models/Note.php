<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'bank_id',
        'user_id',
        'credit_type',
        'invoice_number',
        'date',
        'to',
        'reinsured',
        'Reinsurer',
        'original_insured',
        'PPW',
        'particulars',
    ];

    protected $casts = [
        'particulars' => 'array',
        'date' => 'date',
    ];

    public function quote()
    {
        return $this->belongsTo(\App\Models\Quote::class, 'quote_id');
    }

    public function bank()
    {
        return $this->belongsTo(\App\Models\BankingDetail::class, 'bank_id');
    }
}
