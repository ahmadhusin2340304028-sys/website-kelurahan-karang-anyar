<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Umkm, UmkmImage};
use App\Services\ImageService;
use Illuminate\Http\Request;

class UmkmController extends Controller
{
    public function __construct(private ImageService $imageService) {}

    public function index(Request $request)
    {
        $query = Umkm::with('images')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn ($x) => $x->where('business_name', 'like', "%$q%")
                ->orWhere('owner_name', 'like', "%$q%"));
        }

        $umkms = $query->paginate(15)->withQueryString();

        return view('admin.umkms.index', compact('umkms'));
    }

    public function show(Umkm $umkm)
    {
        $umkm->load('images');
        return view('admin.umkms.show', compact('umkm'));
    }

    public function approve(Umkm $umkm)
    {
        $umkm->update([
            'status'      => 'approved',
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        return redirect()->back()->with('success', "UMKM \"{$umkm->business_name}\" berhasil disetujui.");
    }

    public function reject(Request $request, Umkm $umkm)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $umkm->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->back()->with('success', "UMKM \"{$umkm->business_name}\" berhasil ditolak.");
    }

    public function destroy(Umkm $umkm)
    {
        $this->imageService->delete($umkm->business_photo);
        foreach ($umkm->images as $img) {
            $this->imageService->delete($img->image);
        }
        $umkm->delete();

        return redirect()->route('admin.umkms.index')
            ->with('success', 'Data UMKM berhasil dihapus.');
    }
}
