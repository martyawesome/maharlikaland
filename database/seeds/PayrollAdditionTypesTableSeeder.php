<?php

use App\PayrollAdditionType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PayrollAdditionTypesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('payroll_addition_types')->delete();

        $records = [
            ['id' => 1,
            	'type' => 'Incentive',
                'developer_id' => 1
            ],
            ['id' => 2,
            	'type' => 'Allowance',
                'developer_id' => 1
            ],
            ['id' => 3,
            	'type' => 'Overtime',
                'developer_id' => 1
            ]
            ,
            ['id' => 4,
            	'type' => 'Other',
                'developer_id' => 1
            ]
        ];

        foreach ($records as $record) {
        	PayrollAdditionType::create($record);
        }
        
    }

}