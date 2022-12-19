<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQrCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('customer_id')
                  ->constrained('customers')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->string('attemp')->nullable();
            $table->string('qr_string')->nullable();
            $table->timestamp('createAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->dropColumn('customer_id');
            $table->dropColumn('qr_string');
            $table->dropColumn('createAt');
        });
    }
}
