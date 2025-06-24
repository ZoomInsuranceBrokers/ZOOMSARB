<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankingDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('banking_details', function (Blueprint $table) {
            $table->id();
            $table->string('Bank');
            $table->string('Account_No');
            $table->string('IFSC_Code');
            $table->string('Branch');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('banking_details');
    }
}
