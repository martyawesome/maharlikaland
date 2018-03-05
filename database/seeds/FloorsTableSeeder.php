<?php

use App\Floor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class FloorsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('floors')->delete();

        for($i = 0; $i < 5; $i++) {
            $records[$i] = ['id' => $i + 1, 'floor' => $i + 1];
        }

        foreach ($records as $record) {
        	Floor::create($record);
        }
    }
}
