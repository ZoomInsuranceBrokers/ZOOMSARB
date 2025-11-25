<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlacementSlip extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'reinsurer_name',
        'to',
        'policy_wording',
        'insured_name',
        'insured_address',
        'policy_name',
        'policy_period',
        'currency_type',
        'occupancy',
        'jurisdiction',
        'risk_locations',
        'risk_location_as_per_annexure',
        'property_damage',
        'business_interruption',
        'total_sum_insured',
        'coverage',
        'limit_of_indemnity',
        'indemnity_period',
        'additional_covers',
        'claims',
        'deductibles',
        'premium',
        'support',
        'remark1',
        'remark2',
        'remark3',
        'placement_type',
        'ppw_details',
        'ppc_details',
        'signed_slip',
    ];

    protected $casts = [
        'risk_locations' => 'array',
        'limit_of_indemnity' => 'array',
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }
}