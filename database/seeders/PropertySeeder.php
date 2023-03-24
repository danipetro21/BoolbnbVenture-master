<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\User;
use App\Models\Service;
use App\Models\Sponsorship;


class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


public function run()
{
    DB::transaction(function () {
        $file = fopen(storage_path('app/Boolbnb.csv'), 'r');
    
        // Skip the header row
        fgetcsv($file);
        $user = User::inRandomOrder()->first();
        $sponsorships = Sponsorship::all();
        $counter = 0;
    
        while (($data = fgetcsv($file)) !== false) {
            $property = Property::create([
                'title' => $data[1],
                'description' => $data[2],
                'room_number' => $data[3],
                'bath_number' => $data[4],
                'mq2' => $data[5],
                'address' => $data[6],
                'lat' => $data[7],
                'lon' => $data[8],
                'img' => $data[9] ?? 'home.jpg',
                'visible' => $data[10],
                'price_per_night' => $data[11],
                'bed_number' => $data[12],
                'user_id' => $user->id
            ]);
    
            if ($counter % 20 === 0) {
                $randomSponsorship = $sponsorships->random();
                $start_date = now();
                $end_date = now()->addHours($randomSponsorship->duration_in_hours);
                $property->sponsorships()->attach($randomSponsorship, ['start_date' => $start_date, 'end_date' => $end_date]);
            }
    
            $user = User::inRandomOrder()->first();
            $property->user()->associate($user);
    
            $services = Service::inRandomOrder()->limit(rand(1, 20))->get();
            $property->services()->sync($services);
    
            $property->save();
            $counter++;
        }
    
        fclose($file);
    });
    
    
}


}