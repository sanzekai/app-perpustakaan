<?php

namespace App\Http\Requests; // atau App\Http\Request jika folder proyekmu tidak pakai 's'

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        return true; // Ubah ke true agar request tidak forbidden (403)
    }

    /**
     * Aturan validasi sesuai field tabel members.
     */
    public function rules(): array
    {
        return [
            'nama'          => 'required|string|max:255',
            'nim'           => 'required|string|max:50',
            'email'         => 'required|email|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'alamat'        => 'required|string',
            'status'        => 'required|in:aktif,nonaktif',
        ];
    }
}