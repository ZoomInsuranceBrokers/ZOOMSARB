<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
{
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quote_id');
            $table->unsignedBigInteger('bank_id');
            $table->unsignedBigInteger('user_id');
            $table->string('credit_type');
            $table->string('invoice_number')->nullable();
            $table->date('date')->nullable();
            $table->string('to')->nullable();
            $table->string('reinsured')->nullable();
            $table->string('Reinsurer')->nullable();
            $table->string('original_insured')->nullable();
            $table->string('PPW')->nullable();
            $table->json('particulars')->nullable();
            $table->timestamps();

            $table->foreign('quote_id')->references('id')->on('quotes')->onDelete('cascade');
            $table->foreign('bank_id')->references('id')->on('banking_details')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notes');
    }
}
