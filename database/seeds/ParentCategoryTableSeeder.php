<?php

use Illuminate\Database\Seeder;

class ParentCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\ParentCategory::class, 4)->create();
    }
}
