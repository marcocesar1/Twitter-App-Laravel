<?php

namespace Database\Factories;

use App\Core\Services\RandomImageService;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //'path' => $path,
            'mime_type' => 'image/jpeg'
        ];
    }

    public function image(int $size = 450): Factory
    {
        return $this->state(function (array $attributes) use ($size) {

            $path = 'random_path.png';

            if(config('app.env') != 'testing') {
                $path = RandomImageService::generate($size);
            }

            return [
                'path' => $path,
            ];
        });
    }
}
