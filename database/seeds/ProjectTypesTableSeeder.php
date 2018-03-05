<?php

use App\ProjectType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class ProjectTypesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('project_types')->delete();

        $records = [
            ['id' => 1,
                'project_type' => 'Horizontal'
            ],
            ['id' => 2,
                'project_type' => 'Vertical'
            ]
        ];

        foreach ($records as $record) {
            ProjectType::create($record);
        }
        
    }

}