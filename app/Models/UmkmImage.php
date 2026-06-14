<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UmkmImage extends Model
{
    use HasFactory;

    protected $fillable = ['umkm_id', 'image', 'caption', 'sort_order'];

    public function umkm()
    {
        return $this->belongsTo(Umkm::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && \Storage::disk('public')->exists($this->image)) {
            return \Storage::url($this->image);
        }
        return asset('images/placeholder.png');
    }
}
