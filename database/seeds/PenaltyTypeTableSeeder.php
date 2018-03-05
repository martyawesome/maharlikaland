<?php

use App\PenaltyType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PenaltyTypeTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('penalty_types')->delete();

        $records = [
            ['id' => 1,
                'type' => 'Compounded Penalty only',
                'developer_id' => config('constants.DEVELOPER_ID')
            ],
            ['id' => 2,
                'type' => 'Negative Principal Subtracted',
                'developer_id' => config('constants.DEVELOPER_ID')
            ],
            ['id' => 3,
                'type' => 'True Interest',
                'developer_id' => config('constants.DEVELOPER_ID')
            ]
        ];

        foreach ($records as $record) {
            PenaltyType::create($record);
        }
    }
}
