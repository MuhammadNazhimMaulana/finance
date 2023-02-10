<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeeRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_rules', function (Blueprint $table) {
            $table->id();
            $table->string('xendit_fee_rule_id')->unique()->index();
            $table->string('rule_name');
            $table->string('payment_channel');
            $table->text('description')->nullable();
            $table->string('xendit_unit');
            $table->decimal('xendit_percentage_fee', 32, 2)->default(0);
            $table->integer('xendit_flat_fee')->default(0);
            $table->integer('margin');
            $table->string('currency');
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
        Schema::dropIfExists('fee_rules');
    }
}
