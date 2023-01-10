<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->addGender("Male");
        $this->addGender("Female");
    }

    private function addGender($title)
    {
        $type = Gender::where('title', $title)->first();

        if (!$type) {
            $type = new Gender();
            $type->title = $title;
            $type->save();
        }
    }
}
