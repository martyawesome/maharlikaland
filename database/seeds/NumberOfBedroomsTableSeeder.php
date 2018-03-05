<?php

use App\NumberOfBedroom;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class NumberOfBedroomsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('number_of_bedrooms')->delete();

        $records = [
            ['id' => 1,
                'bedrooms' => '1'
            ],
            ['id' => 2,
                'bedrooms' => '2'
            ],
            ['id' => 3,
                'bedrooms' => '3'
            ],
            ['id' => 4,
                'bedrooms' => '4'
            ],
            ['id' => 5,
                'bedrooms' => '5'
            ],
            ['id' => 6,
                'bedrooms' => '6'
            ],
            ['id' => 7,
                'bedrooms' => '7'
            ],
            ['id' => 8,
                'bedrooms' => '8'
            ],
            ['id' => 9,
                'bedrooms' => '9'
            ],
            ['id' => 10,
                'bedrooms' => '10'
            ]
        ];

        foreach ($records as $record) {
            NumberOfBedroom::create($record);
        }
        
    }

}