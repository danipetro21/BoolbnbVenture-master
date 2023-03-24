<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Services\PropertyService;
use App\Services\LocationService;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{

   // Serve per convertire address
   private $locationService;
   // Serve per creare la proprieta'
  private $propertyService;

  public function __construct(LocationService $locationService, PropertyService $propertyService)
  {
      $this->locationService = $locationService;
      $this->propertyService = $propertyService;
  }

   
        public function updatePropertiesLatLon()
        {
            $properties = Property::all();
            foreach ($properties as $property) {
                $address = $property->address . ', ' . $property->city . ', ' . $property->state . ', ' . $property->zip_code;
                $location = $this->locationService->getLatLngFromAddress($address);
                if ($location) {
                    $property->lat = $location['lat'];
                    $property->lon = $location['lon'];
                    $property->save();
                }
            }
            return response()->json(['message' => 'Properties updated']);
        }


        public function updatePropertiesAddress()
        {
            $properties = Property::all();
            foreach ($properties as $property) {
                $lat = $property->lat;
                $lon = $property->lon;
                $address = $this->locationService->getAddressFromLatLon($lat, $lon);
                if ($address) {
                    $property->address = $address;
                    $property->save();
                }
            }
            return response()->json(['message' => 'Properties updated']);
        }


       
        public function search(Request $request)
        {
          $address = $request->input('address');
          $filters = $request->input('filters', []);
          $properties = collect([]);
          $lat = null;
          $lon = null;
          $radius = $request->input('radius', 20);

        
          if ($address) {
            $latLng = $this->locationService->getLatLngFromAddress($address);
            Log::info('Coordinates:', $latLng);
        
            if (isset($latLng['error'])) {
              return Inertia::render('Properties/PropertySearch', [
                'error' => $latLng['error'],
                'searchAddress' => $address,
              ]);
            }
        
            $lat = $latLng['lat'];
            $lon = $latLng['lon'];

            $filters = $request->input('filters', []);

            $properties = $this->propertyService->getPropertiesWithinRadius($lat, $lon, $radius, $filters);
          } else {
            $properties = $this->propertyService->getAllProperties();
          }
        
          return Inertia::render('Properties/PropertySearch', [
            'properties' => $properties->items(),
            'pagination' => [
              'current_page' => $properties->currentPage(),
              'last_page' => $properties->lastPage(),
              'total' => $properties->total(),
              'from' => $properties->firstItem(),
              'to' => $properties->lastItem(),
            ],
            'searchAddress' => $address,
            'searchLocation' => [$lat, $lon],
          ]);
        }
        
        
        
  
  
}