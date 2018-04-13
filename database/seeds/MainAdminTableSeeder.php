<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;


class MainAdminTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();

        $records = [
            ['id' => 1,
                'username' => 'martyhernandez',
                'password' => Hash::make('iOs54067!'),
                'user_type_id' => 1,
                'is_admin_activated' => true,
                'profile_picture_path' => 'img/defaults/icon-user-default.png'
            ]
        ];

        foreach ($records as $record) {
            User::create($record);
        }
        
    }

}