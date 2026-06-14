<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUmkmRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'owner_name'        => ['required', 'string', 'max:255'],
            'business_name'     => ['required', 'string', 'max:255'],
            'business_category' => ['required', 'string', 'max:100'],
            'description'       => ['required', 'string', 'min:20', 'max:2000'],
            'address'           => ['required', 'string', 'max:500'],
            'phone'             => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'email'             => ['nullable', 'email', 'max:255'],
            'maps_link'         => ['nullable', 'url', 'max:500'],
            'business_photo'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'product_images'    => ['nullable', 'array', 'max:5'],
            'product_images.*'  => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'owner_name.required'        => 'Nama pemilik wajib diisi.',
            'business_name.required'     => 'Nama UMKM wajib diisi.',
            'business_category.required' => 'Kategori usaha wajib dipilih.',
            'description.required'       => 'Deskripsi usaha wajib diisi.',
            'description.min'            => 'Deskripsi minimal 20 karakter.',
            'address.required'           => 'Alamat wajib diisi.',
            'phone.required'             => 'Nomor HP wajib diisi.',
            'phone.regex'                => 'Format nomor HP tidak valid.',
            'email.email'                => 'Format email tidak valid.',
            'maps_link.url'              => 'Link Google Maps harus berupa URL valid.',
            'business_photo.image'       => 'Foto usaha harus berupa gambar.',
            'business_photo.max'         => 'Ukuran foto usaha maksimal 2MB.',
            'product_images.max'         => 'Maksimal 5 foto produk.',
            'product_images.*.image'     => 'Setiap foto produk harus berupa gambar.',
            'product_images.*.max'       => 'Ukuran setiap foto produk maksimal 2MB.',
        ];
    }
}