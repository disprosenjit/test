<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;

class GeocodingController extends Controller
{
    private Client $http;

    public function __construct()
    {
        $this->http = new Client([
            'timeout' => 8,
            'headers' => [
                'User-Agent' => 'RealEstatePro/1.0',
                'Accept'     => 'application/json',
            ],
        ]);
    }

    /**
     * Reverse geocode: lat/lng → address fields.
     * Tries Nominatim first (full street data); falls back to BigDataCloud.
     * GET /geocoding/reverse?lat=...&lng=...
     */
    public function reverse(Request $request): JsonResponse
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        // --- Attempt 1: Nominatim (provides street-level detail) ---
        try {
            $response = $this->http->get('https://nominatim.openstreetmap.org/reverse', [
                'query' => [
                    'format'         => 'json',
                    'lat'            => $request->lat,
                    'lon'            => $request->lng,
                    'zoom'           => 18,
                    'addressdetails' => 1,
                ],
                'headers' => ['Accept-Language' => 'en'],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (!empty($data['address'])) {
                $a = $data['address'];
                $city = $a['city'] ?? $a['town'] ?? $a['village'] ?? $a['municipality'] ?? $a['county'] ?? null;
                $locationLabel = $a['suburb'] ?? $a['neighbourhood'] ?? $a['quarter'] ?? $a['locality'] ?? $city;
                $road = trim(($a['house_number'] ?? '') . ' ' . ($a['road'] ?? '')) ?: null;

                return response()->json([
                    'country'        => $a['country'] ?? null,
                    'state'          => $a['state']   ?? null,
                    'city'           => $city,
                    'location_label' => $locationLabel,
                    'road'           => $road,
                ]);
            }
        } catch (TransferException) {
            // fall through to BigDataCloud
        }

        // --- Attempt 2: BigDataCloud fallback (country/state/city only) ---
        try {
            $response = $this->http->get('https://api.bigdatacloud.net/data/reverse-geocode-client', [
                'query' => [
                    'latitude'         => $request->lat,
                    'longitude'        => $request->lng,
                    'localityLanguage' => 'en',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return response()->json([
                'country'        => $data['countryName']          ?? null,
                'state'          => $data['principalSubdivision'] ?? null,
                'city'           => ($data['city'] ?: ($data['locality'] ?? null)),
                'location_label' => $data['locality']             ?? null,
                'road'           => null,
            ]);
        } catch (TransferException $e) {
            return response()->json(['error' => 'Geocoding service unavailable'], 503);
        }
    }

    /**
     * Forward geocode: address string → lat/lng.
     * Uses Nominatim search endpoint.
     * GET /geocoding/search?q=...
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|max:500',
        ]);

        try {
            $response = $this->http->get('https://nominatim.openstreetmap.org/search', [
                'query' => [
                    'format' => 'json',
                    'q'      => $request->q,
                    'limit'  => 1,
                ],
                'headers' => ['Accept-Language' => 'en'],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (empty($data)) {
                return response()->json(['error' => 'Location not found'], 404);
            }

            return response()->json([
                'lat' => (float) $data[0]['lat'],
                'lng' => (float) $data[0]['lon'],
            ]);
        } catch (TransferException $e) {
            return response()->json(['error' => 'Geocoding service unavailable'], 503);
        }
    }
}
