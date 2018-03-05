<?php

use Illuminate\Database\Seeder;
use App\PaymentType;

class PaymentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_types')->delete();

        $records = [
        	['id' => 1,
                'payment_type' => 'Reservation Fee'
            ],
            ['id' => 2,
                'payment_type' => 'Down payment'
            ],
            ['id' => 3,
                'payment_type' => 'Monthly Amortization'
            ],
            ['id' => 4,
                'payment_type' => 'Penalty Payment'
            ],
            ['id' => 5,
                'payment_type' => 'Penalty Fee'
            ],
            ['id' => 6,
                'payment_type' => 'Full payment'
            ],
            ['id' => 7,
                'payment_type' => 'Bank-finance payment'
            ]
        ];

        foreach ($records as $record) {
            PaymentType::create($record);
        }
    }
}
