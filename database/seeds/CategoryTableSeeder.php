<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('categories')->delete();
        State::create(array('id' => 1, 'parent_id' => -1, 'title' => 'Cleaner'));
        State::create(array('id' => 2, 'parent_id' => -1, 'title' => 'Electrician'));
        State::create(array('id' => 3, 'parent_id' => -1, 'title' => 'Food & Beverage'));
        State::create(array('id' => 4, 'parent_id' => -1, 'title' => 'Gas'));
        State::create(array('id' => 5, 'parent_id' => -1, 'title' => 'Plumber'));
        State::create(array('id' => 6, 'parent_id' => -1, 'title' => 'Recycle'));
        State::create(array('id' => 7, 'parent_id' => -1, 'title' => 'Air Con Service'));
        State::create(array('id' => 8, 'parent_id' => -1, 'title' => 'Pest Control'));
        State::create(array('id' => 9, 'parent_id' => -1, 'title' => 'Tuition'));
        State::create(array('id' => 10, 'parent_id' => -1, 'title' => 'Piano Lesson'));
        State::create(array('id' => 11, 'parent_id' => -1, 'title' => 'Car Wash'));
        State::create(array('id' => 12, 'parent_id' => -1, 'title' => 'Home Repair'));
    }
}
