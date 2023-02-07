<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('invoice_contact_id');
            $table->string('external_id')->unique()->index();
            $table->string('xendit_id')->unique()->index();
            $table->decimal('amount', 32, 2);
            $table->decimal('fee', 32, 2);
            $table->decimal('grand_total_amount', 32, 2);
            $table->string('status')->default('PENDING');
            $table->string('company_name');
            $table->string('company_email')->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('contact_name');
            $table->string('contact_email')->nullable();
            $table->text('contact_address')->nullable();
            $table->string('contact_phone')->nullable();
            $table->longText('xendit_data');
            $table->longText('payment_url');
            $table->timestamps();
            $table->timestamp('expired_at');
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
        Schema::dropIfExists('manual_invoices');
    }
}
