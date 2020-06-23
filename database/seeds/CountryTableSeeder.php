<?php

use Illuminate\Database\Seeder;
use App\Country;

class CountryTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->delete();
        Country::create(array('id' => 1, 'name' => 'Malaysia', 'code' => 'MY'));
        $this->command->info('Country table seeded');
    }
}
