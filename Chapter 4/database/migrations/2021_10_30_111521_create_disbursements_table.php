<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisbursementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disbursements', function (Blueprint $table) {
            $table->id();
            $table->string('category')->index();
            $table->string('external_id')->unique()->index();
            $table->string('xendit_id')->unique()->index();
            $table->string('status')->default('PENDING');
            $table->decimal('amount', 32, 2);
            $table->string('to_name');
            $table->string('to_email');
            $table->string('bank_code');
            $table->string('bank_name');
            $table->string('bank_account_holder_name');
            $table->string('bank_account_number');
            $table->longText('description');
            $table->longText('xendit_data');
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
        Schema::dropIfExists('disbursements');
    }
}
