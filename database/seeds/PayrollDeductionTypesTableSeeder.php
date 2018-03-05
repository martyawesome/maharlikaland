<?php

use App\PayrollDeductionType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class PayrollDeductionTypesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('payroll_deduction_types')->delete();

        $records = [
            ['id' => 1,
            	'type' => 'SSS',
                'developer_id' => 1
            ],
            ['id' => 2,
            	'type' => 'Phil-Health',
                'developer_id' => 1
            ]
        ];

        foreach ($records as $record) {
        	PayrollDeductionType::create($record);
        }
        
    }

}