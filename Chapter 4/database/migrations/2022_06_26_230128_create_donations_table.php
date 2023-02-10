<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique()->index();
            $table->string('xendit_id')->unique()->index();
            $table->string('deskripsi_donasi');
            $table->decimal('amount', 32, 2);
            $table->decimal('fee', 32, 2);
            $table->decimal('grand_total_amount', 32, 2);
            $table->string('status')->default('PENDING');
            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable();
            $table->string('donor_phone')->nullable();
            $table->string('donor_bank')->nullable();
            $table->string('donor_bank_number')->nullable();
            $table->string('person_responsible_name')->nullable();
            $table->string('person_responsible_email')->nullable();
            $table->string('person_responsible_phone')->nullable();
            $table->longText('xendit_data');
            $table->longText('payment_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donations');
    }
}
