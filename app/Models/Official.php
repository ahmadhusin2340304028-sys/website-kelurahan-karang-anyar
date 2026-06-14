<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Official extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'position', 'position_level',
        'photo', 'phone', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static array $positionLevels = [
        'lurah'              => 'Lurah',
        'sekretaris'         => 'Sekretaris',
        'kasi'               => 'Kepala Seksi',
        'staf'               => 'Staf',
        'kepala_lingkungan'  => 'Kepala Lingkungan',
        'ketua_rw'           => 'Ketua RW',
        'ketua_rt'           => 'Ketua RT',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo && \Storage::disk('public')->exists($this->photo)) {
            return \Storage::url($this->photo);
        }
        return asset('images/placeholder-person.png');
    }

    public function getPositionLevelLabelAttribute(): string
    {
        return self::$positionLevels[$this->position_level] ?? $this->position_level;
    }
}
