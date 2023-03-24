<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;


class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            "Cleaning services",
            "Airport transfer",
            "Key exchange",
            "Luggage storage",
            "Concierge services",
            "24-hour check-in",
            "Housekeeping",
            "Bed linens and towels",
            "Laundry services",
            "Pet-friendly accommodations",
            "Child-friendly accommodations",
            "Pool maintenance",
            "Gardening services",
            "Home security",
            "Parking facilities",
            "High-speed internet",
            "Cable TV",
            "Air conditioning",
            "Heating system",
            "Smoke and carbon monoxide detectors"
          ];

          foreach($services as $service){
            $newService = new Service();
            $newService->name = $service;
            $newService->save();
        }
          
    }
}