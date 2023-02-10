<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('external_id')->unique()->index();
            $table->string('xendit_id')->unique()->index();
            $table->decimal('amount', 32, 2);
            $table->string('status')->default('PENDING');
            $table->longText('description');
            $table->longText('xendit_data');
            $table->longText('user_data');
            $table->longText('payment_url')->nullable();
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
        Schema::dropIfExists('topups');
    }
}
