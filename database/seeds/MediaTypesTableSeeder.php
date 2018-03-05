<?php

use App\MediaType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class MediaTypesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('media_types')->delete();

        $records = [
            ['id' => 1,
            	'media_type' => 'Image'
            ],
            ['id' => 2,
            	'media_type' => 'Video'
            ],
            ['id' => 3,
            	'media_type' => 'PDF'
            ]
        ];

        foreach ($records as $record) {
        	MediaType::create($record);
        }
        
    }

}