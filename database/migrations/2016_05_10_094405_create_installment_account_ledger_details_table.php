<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstallmentAccountLedgerDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installment_account_ledger_details', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('installment_account_ledger_id');
            $table->integer('payment_type_id');
            $table->date('payment_date');
            $table->string('ma_covered_date',30);
            $table->string('details_of_payment');
            $table->string('or_no',30)->nullable();
            $table->double('amount_paid');
            $table->double('interest')->nullable();
            $table->double('principal')->nullable();
            $table->double('balance')->nullable();
            $table->string('remarks')->nullable();
            $table->double('penalty')->nullable();
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
        Schema::drop('installment_account_ledger_details');
    }
}
