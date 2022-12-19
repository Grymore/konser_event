<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nama');
            $table->string('telpon');
            $table->string('email');
            $table->integer('kuantiti');
            $table->integer('total');
            $table->string('invoices')->uniqie()->nullable();
            $table->string('status_transaksi')->nullable();
            $table->string('channel_pembayaran')->nullable();
            $table->string('card_payment')->nullable();
            $table->string('virtual_account_number')->nullable();
            $table->string('time_doku_notify')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColoum('nama');
            $table->dropColoum('telpon');
            $table->dropColoum('email');
            $table->dropColoum('kuantiti');
            $table->dropColoum('total');
            $table->dropColoum('invoices');
            $table->dropColoum('status_transaksi');
            $table->dropColoum('channel_pembayaran');
            $table->dropColoum('card_payment');
            $table->dropColoum('virtual_account_number');
            $table->dropColoum('time_doku_notify');
        });
    }
}
