<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest; // Pastikan namespace request sesuai
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Menampilkan daftar anggota (menggunakan data dummy array).
     */
    public function index()
    {
        $members = [
            [
                'id'            => 1,
                'nama'          => 'Ahmad Fadhil',
                'nim'           => '220101001',
                'email'         => 'fadhil@example.com',
                'nomor_telepon' => '081234567890',
                'alamat'        => 'Jl. Sukolilo No. 10, Surabaya',
                'status'        => 'aktif',
            ],
            [
                'id'            => 2,
                'nama'          => 'Budi Santoso',
                'nim'           => '220101002',
                'email'         => 'budi@example.com',
                'nomor_telepon' => '082345678901',
                'alamat'        => 'Jl. Gebang Wetan No. 5, Surabaya',
                'status'        => 'nonaktif',
            ],
        ];

        return view('members.index', compact('members'));
    }

    /**
     * Menampilkan form tambah anggota.
     */
    public function create()
    {
        return view('members.create');
    }

    /**
     * Menyimpan data anggota baru (validasi dengan StoreMemberRequest).
     */
    public function store(StoreMemberRequest $request)
    {
        // Ambil data yang tervalidasi
        $validated = $request->validated();

        // Catatan: Karena belum konek database/model riil di instruksi ini,
        // alurnya langsung redirect dengan flash message sukses
        return redirect()->route('members.index')->with('success', 'Anggota berhasil ditambahkan!');
    }
}