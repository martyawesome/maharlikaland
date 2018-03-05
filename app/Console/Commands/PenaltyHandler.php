<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\InstallmentAccountLedger;
use App\InstallmentAccountLedgerDetail;
use App\PaymentType;

use DB;

class PenaltyHandler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'penalty:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add the penalty of a ledger account';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ledgers = InstallmentAccountLedger::get();
        foreach($ledgers as $ledger){
            $due_date = date($ledger->due_date);
            if(date('d') >= $due_date and !InstallmentAccountLedgerDetail::hasPaidForTheMonth($ledger)) {
                DB::beginTransaction();
                $ledger->penalty_count += 1;
                
                if($ledger->touch()) {
                    DB::commit();
                    if($ledger->penalty_count >= 1){
                        if(PaymentType::getCurrentPayment($ledger) == config('constants.PAYMENT_TYPE_MA')
                         and !InstallmentAccountLedgerDetail::hasPenaltyForMonth($ledger)){
                            InstallmentAccountLedgerDetail::addPenalty($ledger);
                        } else if(InstallmentAccountLedgerDetail::getCurrentPayment($ledger) == config('constants.PAYMENT_TYPE_DOWNPAYMENT')){
                            //Send Notice to buyer
                        }
                    }
                } else {
                    DB::rollback();
                }
            } 
        }

        $this->info('Penalties updated');
    }
}
