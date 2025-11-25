<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('placement_slips', function (Blueprint $table) {
            $table->longText('insured_name')->nullable()->after('id');
            $table->string('insured_address')->nullable()->after('insured_name');
            $table->string('policy_name')->nullable()->after('insured_address');
            $table->string('policy_period')->nullable()->after('policy_name');
            $table->string('currency_type')->nullable()->after('policy_period');
            $table->string('occupancy')->nullable()->after('currency_type');
            $table->longText('jurisdiction')->nullable()->after('occupancy');
            $table->json('risk_locations')->nullable()->after('jurisdiction');
            $table->integer('risk_location_as_per_annexure')->default(0)->after('risk_locations');
            $table->decimal('property_damage', 20, 2)->nullable()->after('risk_location_as_per_annexure');
            $table->decimal('business_interruption', 20, 2)->nullable()->after('property_damage');
            $table->decimal('total_sum_insured', 20, 2)->nullable()->after('business_interruption');
            $table->string('coverage')->nullable()->after('total_sum_insured');
            $table->json('limit_of_indemnity')->nullable()->after('coverage');
            $table->string('indemnity_period')->nullable()->after('limit_of_indemnity');
            $table->longText('additional_covers')->nullable()->after('indemnity_period');
            $table->text('claims')->nullable()->after('additional_covers');
            $table->longText('deductibles')->nullable()->after('claims');
            $table->longText('premium')->nullable()->after('deductibles');
            $table->string('support')->nullable()->after('premium');
            $table->text('remark1')->nullable()->after('support');
            $table->text('remark2')->nullable()->after('remark1');
            $table->text('remark3')->nullable()->after('remark2');
            $table->string('placement_type')->nullable()->after('remark3'); // PPW or PPC
            $table->text('ppw_details')->nullable()->after('placement_type');
            $table->text('ppc_details')->nullable()->after('ppw_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('placement_slips', function (Blueprint $table) {
            $table->dropColumn([
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
            ]);
        });
    }
};