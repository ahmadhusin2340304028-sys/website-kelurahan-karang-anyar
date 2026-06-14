<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Default Admin ────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@kelurahan.id'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('Admin@1234'),
                'role'     => 'superadmin',
            ]
        );

        // ── Default Settings ─────────────────────────────────────────────
        $defaults = [
            // General
            'site_name'        => 'Kelurahan Karang Anyar',
            'kelurahan_name'   => 'Kelurahan Karang Anyar',
            'site_tagline'     => 'Melayani Masyarakat dengan Sepenuh Hati',
            'site_description' => 'Website resmi Kelurahan Karang Anyar. Pusat informasi, berita, dan layanan masyarakat Kelurahan Karang Anyar.',

            // Contact
            'address'      => 'Jl. Karang Anyar No. 1, Kel. Karang Anyar, Kec. Tarakan Barat, Kota Tarakan',
            'phone'        => '(0551) 123456',
            'email'        => 'kelurahan.karanganyar@gmail.com',
            'office_hours' => 'Senin – Jumat: 07.30 – 15.30 WIT',
            'maps_embed'   => '',

            // Profile
            'history'        => 'Kelurahan Karang Anyar merupakan salah satu kelurahan yang berada di wilayah Kecamatan Tarakan Barat, Kota Tarakan, Provinsi Kalimantan Utara.',
            'vision'         => 'Terwujudnya Kelurahan Karang Anyar yang Mandiri, Maju, dan Sejahtera.',
            'mission'        => "1. Meningkatkan kualitas pelayanan publik yang transparan dan akuntabel.\n2. Mendorong partisipasi aktif masyarakat dalam pembangunan.\n3. Mengembangkan potensi ekonomi lokal melalui pemberdayaan UMKM.\n4. Meningkatkan kualitas lingkungan hidup yang bersih dan sehat.",
            'profile'        => 'Kelurahan Karang Anyar adalah kelurahan yang terletak di wilayah Kecamatan Tarakan Barat dengan masyarakat yang aktif dan dinamis.',
            'greeting_lurah' => 'Assalamu\'alaikum Wr. Wb.\n\nPuji syukur kehadirat Allah SWT atas segala limpahan rahmat dan karunia-Nya. Selamat datang di website resmi Kelurahan Karang Anyar.\n\nMelalui website ini, kami berharap dapat memberikan informasi yang bermanfaat bagi seluruh warga dan masyarakat umum.\n\nWassalamu\'alaikum Wr. Wb.',

            // Stats
            'jumlah_penduduk' => '—',
            'jumlah_kk'       => '—',
            'jumlah_rw'       => '—',
            'jumlah_rt'       => '—',
        ];

        Setting::setMany($defaults);

        $this->command->info('✔ Seeder berhasil dijalankan.');
        $this->command->info('  Admin: admin@kelurahan.id | Password: Admin@1234');
        $this->command->warn('  ⚠ Segera ganti password admin setelah login pertama!');
    }
}
