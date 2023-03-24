<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class TomTomService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.tomtom.key');
    }

    public function searchAddress($address)
    {
        $response = Http::get('https://api.tomtom.com/search/2/geocode/' . urlencode($address) . '.json', [
            'key' => $this->apiKey,
        ]);
    
        $responseBody = json_decode($response->body(), true);
    
        // Log the response for debugging
        Log::info('TomTom API response:', $responseBody);
    
        return $responseBody; // Return the entire response object
    }
    



}