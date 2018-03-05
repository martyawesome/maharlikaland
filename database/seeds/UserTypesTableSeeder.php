<?php

use App\UserType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class UserTypesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('user_types')->delete();

        $records = [
            ['id' => 1,
                'user_type' => 'Admin'
            ],
            ['id' => 2,
                'user_type' => 'Broker'
            ],
            ['id' => 3,
                'user_type' => 'Salesperson'
            ],
            ['id' => 4,
                'user_type' => 'Prospect Buyer'
            ],
            ['id' => 5,
                'user_type' => 'Buyer'
            ],
            ['id' => 6,
                'user_type' => 'Developer - Admin'
            ],
            ['id' => 7,
                'user_type' => 'Developer - Secretary'
            ],
            ['id' => 8,
                'user_type' => 'Developer - Accountant'
            ],
            ['id' => 9,
                'user_type' => 'Developer - Employee'
            ],
            ['id' => 10,
                'user_type' => 'Developer - Site'
            ],
            ['id' => 11,
                'user_type' => 'Developer - Guard'
            ]

        ];

        foreach ($records as $record) {
            UserType::create($record);
        }
        
    }

}