<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\View;
use App\Models\Property;


class ViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $properties = Property::all();

        foreach ($properties as $property) {
            $ip = View::factory()->make()->ip; 
            $viewsCount = rand(10, 50); 

            $views = View::factory()->count($viewsCount)->make(['ip' => $ip]);

            foreach ($views as $view) {
                $view->property()->associate($property);
                $view->save();
            }
        }
    }

}