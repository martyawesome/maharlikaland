<?php

use App\NumberOfBathroom;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class NumberOfBathroomsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('number_of_bathrooms')->delete();

        $records = [
            ['id' => 1,
                'bathrooms' => '1'
            ],
            ['id' => 2,
                'bathrooms' => '2'
            ],
            ['id' => 3,
                'bathrooms' => '3'
            ],
            ['id' => 4,
                'bathrooms' => '4'
            ],
            ['id' => 5,
                'bathrooms' => '5'
            ]
        ];

        foreach ($records as $record) {
            NumberOfBathroom::create($record);
        }
        
    }

}