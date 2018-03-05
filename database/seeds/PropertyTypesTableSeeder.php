<?php

use App\PropertyType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class PropertyTypesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('property_types')->delete();

        $records = [
            ['id' => 1,
                'property_type' => 'Single-attached'
            ],
            ['id' => 2,
                'property_type' => 'Single-detached'
            ],
            ['id' => 3,
                'property_type' => 'Townhouse'
            ],
            ['id' => 4,
                'property_type' => 'Lot'
            ],
            ['id' => 5,
                'property_type' => 'Condominium Unit'
            ],
            ['id' => 6,
                'property_type' => 'Commercial Unit'
            ],
            ['id' => 7,
                'property_type' => 'Commercial Building'
            ],
            ['id' => 8,
                'property_type' => 'Resort'
            ]
        ];

        foreach ($records as $record) {
            PropertyType::create($record);
        }
        
    }

}