<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Official;

class StoreOfficialRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'position'       => ['required', 'string', 'max:255'],
            'position_level' => ['required', 'in:' . implode(',', array_keys(Official::$positionLevels))],
            'phone'          => ['nullable', 'string', 'max:20'],
            'sort_order'     => ['nullable', 'integer', 'min:0'],
            'is_active'      => ['boolean'],
            'photo'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'           => 'Nama pejabat wajib diisi.',
            'position.required'       => 'Jabatan wajib diisi.',
            'position_level.required' => 'Level jabatan wajib dipilih.',
            'photo.image'             => 'Foto harus berupa gambar.',
            'photo.max'               => 'Ukuran foto maksimal 1MB.',
        ];
    }
}
