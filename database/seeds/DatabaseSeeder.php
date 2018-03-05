<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(PenaltyTypeTableSeeder::class);
        
        /*$this->call(MediaTypesTableSeeder::class);
        $this->call(NumberOfBathroomsTableSeeder::class);
        $this->call(NumberOfBedroomsTableSeeder::class);
        $this->call(ParkingAvailabilityTableSeeder::class);
        $this->call(ProjectTypesTableSeeder::class);
        $this->call(PropertyStatusesTableSeeder::class);
        $this->call(PropertyTypesTableSeeder::class);
        $this->call(TermsOfPaymentsTableSeeder::class);
        $this->call(UserTypesTableSeeder::class);
        $this->call(FloorsTableSeeder::class);
        $this->call(PaymentTypesTableSeeder::class);
        $this->call(MainAdminTableSeeder::class);
        $this->call(ParkingAvailabilityTableSeeder::class);
        $this->call(PayrollAdditionTypesTableSeeder::class);
        $this->call(PayrollDeductionTypesTableSeeder::class);*/


        Model::reguard();
    }
}
