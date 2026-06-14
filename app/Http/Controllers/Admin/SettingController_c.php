<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ImageService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct(private ImageService $imageService) {}

    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            // Umum
            'site_name'        => ['required', 'string', 'max:255'],
            'kelurahan_name'   => ['required', 'string', 'max:255'],
            'site_tagline'     => ['nullable', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:500'],
            'logo'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:512'],
            'favicon'          => ['nullable', 'image', 'mimes:ico,png', 'max:256'],

            // Profil
            'history'        => ['nullable', 'string'],
            'vision'         => ['nullable', 'string'],
            'mission'        => ['nullable', 'string'],
            'profile'        => ['nullable', 'string'],
            'greeting_lurah' => ['nullable', 'string'],

            // Kontak
            'address'      => ['required', 'string'],
            'phone'        => ['nullable', 'string', 'max:50'],
            'email'        => ['nullable', 'email', 'max:255'],
            'office_hours' => ['nullable', 'string', 'max:255'],
            'maps_embed'   => ['nullable', 'string'],

            // Peta Wilayah
            'peta_wilayah'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'peta_wilayah_judul'      => ['nullable', 'string', 'max:255'],
            'peta_wilayah_keterangan' => ['nullable', 'string', 'max:500'],

            // Statistik
            'jumlah_penduduk' => ['nullable', 'string', 'max:50'],
            'jumlah_kk'       => ['nullable', 'string', 'max:50'],
            'jumlah_rw'       => ['nullable', 'string', 'max:50'],
            'jumlah_rt'       => ['nullable', 'string', 'max:50'],
            'luas_wilayah'    => ['nullable', 'string', 'max:50'],
            'tahun_berdiri'   => ['nullable', 'string', 'max:10'],
        ], [
            'site_name.required'     => 'Nama website wajib diisi.',
            'kelurahan_name.required'=> 'Nama kelurahan wajib diisi.',
            'address.required'       => 'Alamat kantor wajib diisi.',
            'logo.image'             => 'Logo harus berupa file gambar.',
            'logo.max'               => 'Ukuran logo maksimal 512KB.',
            'favicon.max'            => 'Ukuran favicon maksimal 256KB.',
            'peta_wilayah.image'     => 'Peta wilayah harus berupa file gambar.',
            'peta_wilayah.max'       => 'Ukuran gambar peta maksimal 5MB.',
        ]);

        // Ambil semua field teks (kecuali file upload & field khusus)
        $skip = ['_token', '_method', '_active_tab', 'logo', 'favicon', 'peta_wilayah', 'hapus_peta_wilayah'];
        $data = collect($request->except($skip))
            ->filter(fn ($val) => !is_null($val))
            ->toArray();

        // ── Logo ─────────────────────────────────────────────────────────
        if ($request->hasFile('logo')) {
            $oldLogo = Setting::get('logo');
            $this->imageService->delete($oldLogo);
            $data['logo'] = $this->imageService->upload($request->file('logo'), 'settings');
        }

        // ── Favicon ───────────────────────────────────────────────────────
        if ($request->hasFile('favicon')) {
            $oldFavicon = Setting::get('favicon');
            $this->imageService->delete($oldFavicon);
            $data['favicon'] = $this->imageService->upload($request->file('favicon'), 'settings');
        }

        // ── Peta Wilayah: hapus jika dicentang ───────────────────────────
        if ($request->boolean('hapus_peta_wilayah')) {
            $this->imageService->delete(Setting::get('peta_wilayah'));
            $data['peta_wilayah'] = null;
        }

        // ── Peta Wilayah: upload baru ─────────────────────────────────────
        if ($request->hasFile('peta_wilayah')) {
            $oldPeta = Setting::get('peta_wilayah');
            $this->imageService->delete($oldPeta);
            $data['peta_wilayah'] = $this->imageService->upload(
                $request->file('peta_wilayah'), 'settings/peta'
            );
        }

        Setting::setMany($data);

        // Redirect kembali ke tab yang aktif
        $activeTab = $request->input('_active_tab', 'tab-general');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil disimpan.')
            ->with('active_tab', $activeTab);
    }
}
