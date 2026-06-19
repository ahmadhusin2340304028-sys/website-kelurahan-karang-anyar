<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(15);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => ['required', 'string', 'max:255'],
            'content'    => ['required', 'string', 'min:5'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active'  => ['boolean'],
        ], [
            'title.required'      => 'Judul pengumuman wajib diisi.',
            'title.max'           => 'Judul maksimal 255 karakter.',
            'content.required'    => 'Isi pengumuman wajib diisi.',
            'content.min'         => 'Isi pengumuman minimal 5 karakter.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal berakhir tidak boleh sebelum tanggal mulai.',
        ]);

        Announcement::create([
            'title'      => $validated['title'],
            'content'    => $validated['content'],
            'start_date' => $validated['start_date'],
            'end_date'   => $validated['end_date'] ?? null,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail pengumuman sebagai JSON (dipakai oleh modal publik via fetch, opsional).
     */
    public function show(Announcement $announcement)
    {
        return response()->json([
            'id'         => $announcement->id,
            'title'      => $announcement->title,
            'content'    => $announcement->content,
            'start_date' => $announcement->start_date->translatedFormat('d F Y'),
            'end_date'   => $announcement->end_date?->translatedFormat('d F Y'),
            'is_active'  => $announcement->is_active,
        ]);
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title'      => ['required', 'string', 'max:255'],
            'content'    => ['required', 'string', 'min:5'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active'  => ['boolean'],
        ], [
            'title.required'      => 'Judul pengumuman wajib diisi.',
            'title.max'           => 'Judul maksimal 255 karakter.',
            'content.required'    => 'Isi pengumuman wajib diisi.',
            'content.min'         => 'Isi pengumuman minimal 5 karakter.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal berakhir tidak boleh sebelum tanggal mulai.',
        ]);

        $announcement->update([
            'title'      => $validated['title'],
            'content'    => $validated['content'],
            'start_date' => $validated['start_date'],
            'end_date'   => $validated['end_date'] ?? null,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}