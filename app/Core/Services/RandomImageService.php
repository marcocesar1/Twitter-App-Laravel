<?php

namespace App\Core\Services;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class RandomImageService {
    static function generate(int $size): string
    {
        $filePath = "";
        
        try {
            $randomName = Str::random(40);
            
            $client = new Client();
            $url = "https://picsum.photos/{$size}";
            $response = $client->get($url);
            $imageContent = $response->getBody()->getContents();
            $filePath = "random/{$randomName}.jpg";

            Storage::put($filePath, $imageContent);
        } catch (Exception $e) {
            Log::error("Error saving random image: {$e->getMessage()}");
        }

        return $filePath;
    }
}