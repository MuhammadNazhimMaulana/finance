<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToEmployeeBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employe_banks', function (Blueprint $table) {
            $table->string('status')->default(\App\Models\EmployeBank::STATUS_INACTIVE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employe_banks', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
