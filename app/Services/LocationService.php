<?php

namespace App\Services;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Services\TomTomService;
use Illuminate\Support\Facades\Log;


class LocationService
{
    public function getLatLngFromAddress($address)
    {
        $tomTomService = new TomTomService();
        $results = $tomTomService->searchAddress($address);
    
        if (!isset($results['results']) || empty($results['results'])) {
            // Return an error or handle the case when the "results" key is not available
            return ['error' => 'No results found for the given address.'];
        }
    
        $lat = $results['results'][0]['position']['lat'];
        $lon = $results['results'][0]['position']['lon'];
        
        
    
        return ['lat' => $lat, 'lon' => $lon];
    }

    public function getAddressFromLatLon($lat, $lon)
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lon&key=AIzaSyD8J507aXre7j_AUzqbCf2U5_dSnMo67Dg";
        $response = json_decode(file_get_contents($url), true);

        if ($response['status'] === 'OK') {
            return $response['results'][0]['formatted_address'];
        } else {
            // Return an error or handle the case when the status is not OK
            return ['error' => 'Unable to get address for the given latitude and longitude.'];
        }
    }
        

}