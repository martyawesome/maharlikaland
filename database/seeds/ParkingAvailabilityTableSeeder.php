<?php

use App\ParkingAvailability;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class ParkingAvailabilityTableSeeder extends Seeder {

    public function run()
    {
        DB::table('parking_availability')->delete();

        $records = [
            ['id' => 1,
                'parking_availability' => 'Without Parking Space'
            ],
            ['id' => 2,
                'parking_availability' => 'With Parking Space'
            ],
            ['id' => 3,
                'parking_availability' => 'With Parking Space (Additional Fees)'
            ]
        ];

        foreach ($records as $record) {
            ParkingAvailability::create($record);
        }
        
    }

}