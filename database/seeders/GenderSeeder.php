<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    public function run()
    {
        $this->addGenero(Gender::MALE, "Male");
        $this->addGenero(Gender::FEMALE, "Female");
    }
    
    private function addGenero($id, $title){
        $e = Gender::where('id', $id)->first();
        if(!$e){
           $e = new Gender();
        }
        $e->title = $title;
        $e->save(); 
    }
}
