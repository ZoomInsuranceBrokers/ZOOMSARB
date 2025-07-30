<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReinsurerNameAndTotalPremiumToNotesTable extends Migration
{
    public function up()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->string('reinsurer_name')->nullable()->after('Reinsurer');
            $table->decimal('total_premium', 15, 2)->nullable()->after('reinsurer_name');
        });
    }

    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn('reinsurer_name');
            $table->dropColumn('total_premium');
        });
    }
}
