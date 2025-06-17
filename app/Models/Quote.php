<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'insured_name',
        'user_id',
        'insured_address',
        'policy_name',
        'policy_period',
        'occupancy',
        'jurisdiction',
        'risk_locations',
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
        'is_active',
        'is_submit',
        'is_edit',
        'is_final_submit',
        'policy_wording',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'risk_locations' => 'array',
        'limit_of_indemnity' => 'array',
        'property_damage' => 'decimal:2',
        'business_interruption' => 'decimal:2',
        'total_sum_insured' => 'decimal:2',
        'is_active' => 'boolean',
        'is_submit' => 'boolean',
        'is_edit' => 'boolean',
        'is_final_submit' => 'boolean',
    ];
}
