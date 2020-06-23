<?php

use Illuminate\Database\Seeder;
use App\State;

class StateTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('states')->delete();
        State::create(array('id' => 1, 'country_id' => 1, 'name' => 'Johor'));
        State::create(array('id' => 2, 'country_id' => 1, 'name' => 'Kedah'));
        State::create(array('id' => 3, 'country_id' => 1, 'name' => 'Kelantan'));
        State::create(array('id' => 4, 'country_id' => 1, 'name' => 'Kuala Lumpur'));
        State::create(array('id' => 5, 'country_id' => 1, 'name' => 'Labuan'));
        State::create(array('id' => 6, 'country_id' => 1, 'name' => 'Melaka'));
        State::create(array('id' => 7, 'country_id' => 1, 'name' => 'Negeri Sembilan'));
        State::create(array('id' => 8, 'country_id' => 1, 'name' => 'Pahang'));
        State::create(array('id' => 9, 'country_id' => 1, 'name' => 'Penang'));
        State::create(array('id' => 10, 'country_id' => 1, 'name' => 'Perak'));
        State::create(array('id' => 11, 'country_id' => 1, 'name' => 'Perlis'));
        State::create(array('id' => 12, 'country_id' => 1, 'name' => 'Putrajaya'));
        State::create(array('id' => 13, 'country_id' => 1, 'name' => 'Sabah'));
        State::create(array('id' => 14, 'country_id' => 1, 'name' => 'Sarawak'));
        State::create(array('id' => 15, 'country_id' => 1, 'name' => 'Selangor'));
        State::create(array('id' => 16, 'country_id' => 1, 'name' => 'Terengganu'));
        $this->command->info('State table seeded');
    }
}
