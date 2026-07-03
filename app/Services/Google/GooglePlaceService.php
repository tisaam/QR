<?php

namespace App\Services\Google;

use Illuminate\Support\Facades\Http;

class GooglePlaceService
{
    private $apiKey;
    private $baseUrl = 'https://maps.googleapis.com/maps/api/place';

    public function __construct()
    {
        $this->apiKey = config('services.google.places_api_key');
    }

    public function searchPlaces(string $query, string $location = 'India')
    {
        $response = Http::get($this->baseUrl . '/textsearch/json', [
            'query' => $query . ' ' . $location,
            'key' => $this->apiKey,
        ]);
        return $response->json('results', []);
    }

    public function getPlaceDetails(string $placeId)
    {
        $response = Http::get($this->baseUrl . '/details/json', [
            'place_id' => $placeId,
            'fields' => 'name,formatted_address,formatted_phone_number,website,rating',
            'key' => $this->apiKey,
        ]);
        return $response->json('result');
    }

    public function getReviewLink(string $placeId): string
    {
        return "https://search.google.com/local/writereview?placeid={$placeId}";
    }
}