<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeeTotal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topups', function (Blueprint $table) {
            $table->integer('fee')->default(0);
            $table->decimal('total', 32, 2)->default(0);
        });

        Schema::table('employe_salaries', function (Blueprint $table) {
            $table->integer('fee')->default(0);
            $table->decimal('total', 32, 2)->default(0);
        });

        Schema::table('disbursements', function (Blueprint $table) {
            $table->integer('fee')->default(0);
            $table->decimal('total', 32, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('topups', function (Blueprint $table) {
            $table->dropColumn('fee');
            $table->dropColumn('total');
        });

        Schema::table('employe_salaries', function (Blueprint $table) {
            $table->dropColumn('fee');
            $table->dropColumn('total');
        });

        Schema::table('disbursements', function (Blueprint $table) {
            $table->dropColumn('fee');
            $table->dropColumn('total');
        });
    }
}
