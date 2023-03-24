<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;

use App\Models\Property;
use App\Models\Service;
class PropertyService

{
    public function createProperty($data, $user_id, $location, $img_path )
    {
        $property = new Property;
        $property->title = $data['title'];
        $property->description = $data['description'];
        $property->room_number = $data['room_number'];
        $property->bath_number = $data['bath_number'];
        $property->bed_number = $data['bed_number'];
        $property->visible = $data['visible'];
        $property->mq2 = $data['mq2'];
        $property->address = $data['address'];
        
        if ($location) {
            $property->lat = $location['lat']; 
            $property->lon = $location['lon']; 
        } else {
            $property->lat = 0;
            $property->lon = 0;
        }
        
        $property->img = str_replace('public/', '', $img_path);
        $property->price_per_night = $data['price_per_night'];
        $property->user_id = $user_id;
        $property->save();

        
         if (!empty($data['services'])) {
        $services = Service::whereIn('id', $data['services'])->get();
        $property->services()->saveMany($services);
    }
        return $property;
    }


    public function getPropertiesWithinRadius($lat, $lon, $radius, $filters = [], $perPage = 10)
    {
      $properties = Property::selectRaw('*, ( 6371 * acos( cos( radians(?) ) * cos( radians( lat ) ) * cos( radians( lon ) - radians(?) ) + sin( radians(?) ) * sin( radians( lat) ) ) ) AS distance', [$lat, $lon, $lat])
        ->having('distance', '<=', $radius);
    
        if (!empty($filters)) {
          foreach ($filters as $filter => $value) {
              if ($value) {
                  $properties = $properties->where(function ($query) use ($filter, $value) {
                      $query->where($filter, '<=', $value);
                  });
              }
          }
      }
    
      $properties = $properties->orderBy('distance', 'asc')->paginate($perPage);
    
      return $properties;
    }



    
    public function getAllProperties($perPage = 10)
     {
            return Property::paginate($perPage);
     }

    

    
}