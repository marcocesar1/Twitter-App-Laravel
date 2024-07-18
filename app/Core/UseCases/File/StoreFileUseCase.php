<?php

namespace App\Core\UseCases\File;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StoreFileUseCase {
    public function execute(UploadedFile $file, User $user): File
    {
        $mime = $file->getMimeType();

        $path = $file->store("posts/{$user->id}", 'public');

        $file = new File();
        $file->path = $path;
        $file->mime_type = $mime;
        $file->save();

        //$file->url = Storage::url($path);

        return $file;
    }
}