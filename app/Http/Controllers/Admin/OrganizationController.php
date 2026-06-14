<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationStructure;
use App\Services\ImageService;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function __construct(private ImageService $imageService) {}

    public function index()
    {
        $structures = OrganizationStructure::latest()->paginate(10);
        return view('admin.organization.index', compact('structures'));
    }

    public function create()
    {
        return view('admin.organization.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'image'       => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active'   => ['boolean'],
        ]);

        $path = $this->imageService->upload($request->file('image'), 'organization');

        // Nonaktifkan yang lama jika yang baru aktif
        if ($request->boolean('is_active', true)) {
            OrganizationStructure::where('is_active', true)->update(['is_active' => false]);
        }

        OrganizationStructure::create([
            'title'       => $request->title,
            'image'       => $path,
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.organization.index')
            ->with('success', 'Struktur organisasi berhasil ditambahkan.');
    }

    public function edit(OrganizationStructure $organization)
    {
        return view('admin.organization.edit', compact('organization'));
    }

    public function update(Request $request, OrganizationStructure $organization)
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active'   => ['boolean'],
        ]);

        $data = $request->only(['title', 'description']);
        $data['is_active'] = $request->boolean('is_active', false);

        if ($request->hasFile('image')) {
            $data['image'] = $this->imageService->replace(
                $organization->image, $request->file('image'), 'organization'
            );
        }

        if ($data['is_active']) {
            OrganizationStructure::where('id', '!=', $organization->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $organization->update($data);

        return redirect()->route('admin.organization.index')
            ->with('success', 'Struktur organisasi berhasil diperbarui.');
    }

    public function destroy(OrganizationStructure $organization)
    {
        $this->imageService->delete($organization->image);
        $organization->delete();

        return redirect()->route('admin.organization.index')
            ->with('success', 'Struktur organisasi berhasil dihapus.');
    }
}
