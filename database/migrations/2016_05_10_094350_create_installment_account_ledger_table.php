<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstallmentAccountLedgerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installment_account_ledger', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->integer('property_id');
            $table->integer('buyer_id');
            $table->string('tct_no')->nullable();
            $table->double('tcp');
            $table->double('dp_percentage');
            $table->double('dp');
            $table->double('balance');
            $table->double('reservation_fee');
            $table->double('mo_interest');
            $table->double('mo_amortization');
            $table->date('contract_date')->nullable();
            $table->integer('years_to_pay');
            $table->string('due_date',10);
            $table->integer('penalty_count');
            $table->double('floating')->nullable();
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
        Schema::drop('installment_account_ledger');
    }
}
