<?php

use App\PropertyStatus;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class PropertyStatusesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('property_statuses')->delete();

        $records = [
            ['id' => 1,
                'property_status' => 'For Sale'
            ],
            ['id' => 2,
                'property_status' => 'For Rent'
            ],
            ['id' => 3,
                'property_status' => 'Reserved'
            ],
            ['id' => 4,
                'property_status' => 'Foreclosed'
            ],
            ['id' => 5,
                'property_status' => 'Sold - Ongoing DP'
            ],
            ['id' => 6,
                'property_status' => 'Sold - Ongoing MA'
            ],
            ['id' => 7,
                'property_status' => 'Fully Paid'
            ],
            ['id' => 8,
                'property_status' => 'Bank-financed'
            ]
        ];

        foreach ($records as $record) {
            PropertyStatus::create($record);
        }
        
    }

}