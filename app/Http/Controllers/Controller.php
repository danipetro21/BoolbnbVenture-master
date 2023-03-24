<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Property;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    // Funzione per la ricerca
    public function search(Request $request)
    {
        $query = Property::query();

        if ($request->has('latitude') && $request->has('longitude')) {
            $lat = $request->input('latitude');
            $lon = $request->input('longitude');
            $radius = $request->input('radius', 20); // Default radius is 20km

            // Calculate the bounding box coordinates based on the search location and radius
            $earthRadius = 6371; // km
            $latDelta = $radius / $earthRadius;
            $lonDelta = abs(asin(sin($latDelta) / cos(deg2rad($lat))) * 180 / M_PI);
            $latMin = $lat - $latDelta;
            $latMax = $lat + $latDelta;
            $lonMin = $lon - $lonDelta;
            $lonMax = $lon + $lonDelta;

            // Get the properties within the specified bounding box
            $properties = $query->whereBetween('lat', [$latMin, $latMax])
                ->whereBetween('lon', [$lonMin, $lonMax])
                ->get()
                ->filter(function ($property) use ($lat, $lon, $radius, $earthRadius) {
                    // Calculate the distance between the property and the search location using the Haversine formula
                    $propertyLat = $property->lat;
                    $propertyLon = $property->lon;
                    $dLat = deg2rad($propertyLat - $lat);
                    $dLon = deg2rad($propertyLon - $lon);
                    $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat)) * cos(deg2rad($propertyLat)) * sin($dLon / 2) * sin($dLon / 2);
                    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                    $distance = $earthRadius * $c;

                    // Check if the distance is within the specified radius
                    return $distance <= $radius;
                });
        } else {
            // If the latitude and longitude are not set, get all the properties
            $properties = $query->get();
        }

        return response()->json(['properties' => $properties]);
    }

       
}