<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('insured_name')->nullable();
            $table->string('insured_address')->nullable();
            $table->string('policy_name')->nullable();
            $table->string('policy_period')->nullable();
            $table->string('occupancy')->nullable();
            $table->string('jurisdiction')->nullable();
            $table->json('risk_locations')->nullable();
            $table->decimal('property_damage', 20, 2)->nullable();
            $table->decimal('business_interruption', 20, 2)->nullable();
            $table->decimal('total_sum_insured', 20, 2)->nullable();
            $table->string('coverage')->nullable();
            $table->json('limit_of_indemnity')->nullable();
            $table->string('indemnity_period')->nullable();
            $table->string('additional_covers')->nullable();
            $table->text('claims')->nullable();
            $table->string('deductibles')->nullable();
            $table->string('premium')->nullable();
            $table->string('support')->nullable();
            $table->text('remark1')->nullable();
            $table->text('remark2')->nullable();
            $table->text('remark3')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_submit')->default(0);
            $table->boolean('is_edit')->default(1);
            $table->boolean('is_final_submit')->default(0);
            $table->longText('policy_wording')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotes');
    }
}
