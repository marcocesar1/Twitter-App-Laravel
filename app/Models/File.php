<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['path', 'mime_type'];
    protected $appends = ['url'];
    //protected $hidden = ['mime_type', 'path', 'created_at', 'updated_at'];

    protected function getUrlAttribute()
    {
        return Storage::url(
            $this->path
        );
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_file');
    }
}
