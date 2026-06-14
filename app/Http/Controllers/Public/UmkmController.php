<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUmkmRequest;
use App\Models\Umkm;
use App\Models\UmkmImage;
use App\Services\ImageService;
use Illuminate\Http\Request;

class UmkmController extends Controller
{
    public function __construct(private ImageService $imageService) {}

    public function index(Request $request)
    {
        $query = Umkm::with('images')->approved();

        if ($request->filled('category')) {
            $query->where('business_category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%");
            });
        }

        $umkms = $query->paginate(12)->withQueryString();
        $categories = Umkm::$categories;

        return view('public.umkm.index', compact('umkms', 'categories'));
    }

    public function create()
    {
        $categories = Umkm::$categories;
        return view('public.umkm.create', compact('categories'));
    }

    public function store(StoreUmkmRequest $request)
    {
        $data = $request->validated();

        // Upload business photo
        if ($request->hasFile('business_photo')) {
            $data['business_photo'] = $this->imageService->upload(
                $request->file('business_photo'), 'umkm'
            );
        }
        unset($data['product_images']);

        $umkm = Umkm::create($data);

        // Upload product images
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $index => $image) {
                $path = $this->imageService->upload($image, 'umkm/products');
                UmkmImage::create([
                    'umkm_id'    => $umkm->id,
                    'image'      => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('umkm.success')
            ->with('success', 'Data UMKM berhasil dikirim. Mohon tunggu proses verifikasi dari admin.');
    }

    public function success()
    {
        return view('public.umkm.success');
    }
}
