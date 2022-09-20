<?php

namespace App\Models;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Task extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['title', 'description'];

        public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->fit(Manipulations::FIT_MAX, 1000, 1000);
    }
}