<?php

namespace App\Http\Controllers;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePropertyRequest;
use App\Services\PropertyService;
use App\Services\LocationService;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdatePropertyRequest;
use Illuminate\Support\Facades\Storage;
use Image;





class UserPropertyController extends Controller
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


     // Mostra le proprieta'
     public function index()
     {
        $loggedInUserId = Auth::user()->id;
        $properties = Property::with('services')->where('user_id', $loggedInUserId)->get();
        $propertiesArray = $properties->toArray();

         return inertia('Dashboard', [
             'properties' => $propertiesArray,
         ]);

     }

    // Recupera le proprieta' dell'utente loggato per mostrarle 
    public function getUserProperties($userId)
    {
        //prende le proprietà nelle quali lo user_id matcha con lo userId che viene passato
        $properties = Property::with('sponsorships')->where('user_id', $userId)->get();
        
        //$sponsorships = Sponsorship::all();

        return response()->json($properties);
    }
    
     // Crea le proprieta'
     public function create(){
        $services = Service::all();
        
        return inertia ('Properties/PropertyCreate',[
            'services' => $services,
        ]);
     }

        //  Salva le proprieta
        public function store(StorePropertyRequest $request)
        {
            $validatedData = $request->validated();
            
            $location = $this->locationService->getLatLngFromAddress($validatedData['address']);
            
            $img_path = null;
            if ($request->hasFile('img')) {
                $img = $request->file('img');
                
                $img_path = $img->store('properties', 's3');
                
                // Add the S3 bucket URL to the file path
                $img_path = 'https://boolbnb.s3.eu-west-2.amazonaws.com/' . $img_path;
            }
            
            $this->propertyService->createProperty($validatedData, auth()->id(), $location, $img_path);
            
            return redirect()->route('properties.index')->with('message', 'Property created successfully');
        }


        // Elimina le proprieta'
        public function destroy(Property $property)
        {
            DB::transaction(function () use ($property) {
                // Elimina i record in altre tabelle che fanno riferimento alla proprietà
                $property->services()->detach();
                $property->sponsorships()->detach();
                $property->views()->delete();
                $property->messages()->delete();
        
                // Elimina la proprietà dal database
                $property->delete();
            });

            //    return redirect()->route('properties.index')->with('message', 'Property deleted successfully');
        
            return redirect()->back()
                ->with('message', 'Property deleted');
        
                
        }
     
        // Modifica le proprieta'
        public function edit($id)
        {
            $property = Property::with('services')->findOrFail($id);
        
            $services = Service::all();
        
            // Modify the image path to use the S3 bucket URL prefix
            $image = str_replace('properties/', 'https://boolbnb.s3.eu-west-2.amazonaws.com/properties/', $property->img);
        
            return inertia('Properties/PropertyEdit', compact('property', 'services', 'image'));
        }

        // Aggiorna le proprieta'
        public function update(UpdatePropertyRequest $request, Property $property)
        {
            $validatedData = $request->validated();

            // Get the new latitude and longitude from the updated address
            $location = app(LocationService::class)->getLatLngFromAddress($validatedData['address']);

            // Handle the image update
            if ($request->hasFile('img')) {
                // Delete the old image
                // Storage::delete('public/'. $property->img);
                // Store the new image
                $img_path = $request->file('img')->store('properties', 's3');
                // Modify the image path to use the S3 bucket URL prefix
                $validatedData['img'] = 'https://boolbnb.s3.eu-west-2.amazonaws.com/' . $img_path;
            }

            $property->update($validatedData);

            // Update the latitude and longitude of the property
            $property->lat = $location['lat'];
            $property->lon = $location['lon'];
            $property->save();

            // Detach all existing services
            $property->services()->detach();

            // Attach the selected services
            if ($request->has('services')) {
                $property->services()->sync($request->input('services'));
            }

            return redirect()->route('properties.index')->with('message', 'Property updated successfully');
        }

    
       
        

   

}