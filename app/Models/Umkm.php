<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Umkm extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_name', 'business_name', 'business_category',
        'description', 'address', 'phone', 'email',
        'maps_link', 'business_photo', 'status',
        'rejection_reason', 'approved_at',
    ];

    protected $casts = ['approved_at' => 'datetime'];

    public static array $categories = [
        'Kuliner', 'Fashion', 'Kerajinan', 'Pertanian', 'Peternakan',
        'Perikanan', 'Jasa', 'Perdagangan', 'Teknologi', 'Lainnya',
    ];

    // ─── Relationships ────────────────────────────────────────────
    public function images()
    {
        return $this->hasMany(UmkmImage::class)->orderBy('sort_order');
    }

    // ─── Scopes ───────────────────────────────────────────────────
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // ─── Accessors ────────────────────────────────────────────────
    public function getBusinessPhotoUrlAttribute(): string
    {
        if ($this->business_photo && \Storage::disk('public')->exists($this->business_photo)) {
            return \Storage::url($this->business_photo);
        }
        return asset('images/placeholder-umkm.png');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'approved' => '<span class="badge bg-success">Disetujui</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
            default    => '<span class="badge bg-warning text-dark">Menunggu</span>',
        };
    }

    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }
}
