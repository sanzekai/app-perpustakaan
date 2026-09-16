<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest; // Pastikan namespace request sesuai
use Illuminate\Http\Request;

class MemberController extends Controller
{
    // 1. Properti ditaruh di sini (di dalam class, di luar fungsi)
    private array $members = [
        ['id' => 1, 'nama' => 'Siti Aminah', 'nim' => '2310501001', 'email' => 'siti.aminah@pens.ac.id', 'nomor_telepon' => '081234567890', 'status' => 'aktif'],
        ['id' => 2, 'nama' => 'Budi Santoso', 'nim' => '2310501002', 'email' => 'budi.santoso@pens.ac.id', 'nomor_telepon' => '081298765432', 'status' => 'aktif'],
        ['id' => 3, 'nama' => 'Dewi Lestari', 'nim' => '2310501003', 'email' => 'dewi.lestari@pens.ac.id', 'nomor_telepon' => '081211122233', 'status' => 'nonaktif'],
    ];

    /**
     * Menampilkan daftar anggota.
     */
    // 2. Fungsi index lama diganti dengan yang memanggil $this->members
    public function index()
    {
        $members = $this->members;

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