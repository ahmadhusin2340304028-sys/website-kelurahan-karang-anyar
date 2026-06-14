<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOfficialRequest;
use App\Models\Official;
use App\Services\ImageService;
use Illuminate\Http\Request;

class OfficialController extends Controller
{
    public function __construct(private ImageService $imageService) {}

    public function index()
    {
        $officials = Official::ordered()->paginate(20);
        return view('admin.officials.index', compact('officials'));
    }

    public function create()
    {
        $positionLevels = Official::$positionLevels;
        return view('admin.officials.create', compact('positionLevels'));
    }

    public function store(StoreOfficialRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->imageService->upload($request->file('photo'), 'officials');
        }
        Official::create($data);

        return redirect()->route('admin.officials.index')
            ->with('success', 'Data pejabat berhasil ditambahkan.');
    }

    public function edit(Official $official)
    {
        $positionLevels = Official::$positionLevels;
        return view('admin.officials.edit', compact('official', 'positionLevels'));
    }

    public function update(StoreOfficialRequest $request, Official $official)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->imageService->replace($official->photo, $request->file('photo'), 'officials');
        }
        $official->update($data);

        return redirect()->route('admin.officials.index')
            ->with('success', 'Data pejabat berhasil diperbarui.');
    }

    public function destroy(Official $official)
    {
        $this->imageService->delete($official->photo);
        $official->delete();

        return redirect()->route('admin.officials.index')
            ->with('success', 'Data pejabat berhasil dihapus.');
    }
}
