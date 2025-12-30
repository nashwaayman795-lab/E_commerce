<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Product extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'user_id' 
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

     public function registerMediaCollections(): void{
        $this->addMediaCollection('cover')->singleFile(); 
    }

         public function getCoverUrlAttribute(): ?string{
    $cover = $this->getFirstMedia('cover');
    return $cover ? $cover->getUrl() : null;
}
}


