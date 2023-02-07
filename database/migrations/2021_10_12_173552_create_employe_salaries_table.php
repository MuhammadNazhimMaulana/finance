<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employe_salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employe_id');
            $table->unsignedBigInteger('employe_bank_id');
            $table->string('external_id')->unique()->index();
            $table->string('xendit_id')->unique()->index();
            $table->string('status')->default('PENDING');
            $table->date('salary_date');
            $table->decimal('amount', 32, 2);
            $table->longText('description');
            $table->longText('xendit_data');
            $table->longText('meta_data')->nullable();
            $table->integer('try_count')->default(1);
            $table->longText('employe_data');
            $table->longText('employe_bank_data');
            $table->longText('transferred_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employe_salaries');
    }
}
