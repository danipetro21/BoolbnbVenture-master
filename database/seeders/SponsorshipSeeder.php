<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sponsorship;

class SponsorshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sponsorship = new Sponsorship();
        $sponsorship->type = "Zen Package";
        $sponsorship->cost = 2.99;
        $sponsorship->duration = "24:00:00";

        $sponsorship->save();

        $sponsorship = new Sponsorship();
        $sponsorship->type = "Oasis Package";
        $sponsorship->cost = 5.99;
        $sponsorship->duration = "72:00:00";
         
        $sponsorship->save();

        $sponsorship = new Sponsorship();
        $sponsorship->type = "Infinity Package";
        $sponsorship->cost = 9.99;
        $sponsorship->duration = "144:00:00";

        $sponsorship->save();
    }
}