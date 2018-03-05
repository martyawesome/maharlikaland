<?php

use App\TermsOfPayment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class TermsOfPaymentsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('terms_of_payments')->delete();

        $records = [
            ['id' => 1,
                'terms_of_payment' => 'Bank-finance'
            ],
            ['id' => 2,
                'terms_of_payment' => 'Pag-ibig'
            ],
            ['id' => 3,
                'terms_of_payment' => 'In-house finance'
            ]
        ];

        foreach ($records as $record) {
            TermsOfPayment::create($record);
        }
        
    }

}