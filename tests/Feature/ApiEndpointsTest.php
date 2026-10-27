<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;
    protected User $user;
    protected Member $member;
    protected Book $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'nama_kategori' => 'Teknologi Informasi',
            'deskripsi' => 'Buku-buku IT dan Pemrograman',
        ]);

        $this->user = User::create([
            'name' => 'Petugas Perpustakaan',
            'email' => 'petugas@perpustakaan.test',
            'password' => bcrypt('password'),
            'role' => 'petugas',
        ]);

        $this->member = Member::create([
            'nama' => 'Ahmad Dahlan',
            'nim' => '3120600001',
            'email' => 'ahmad@mahasiswa.test',
            'nomor_telepon' => '081234567890',
            'alamat' => 'Surabaya',
            'status' => 'aktif',
        ]);

        $this->book = Book::create([
            'judul' => 'Belajar Laravel 12',
            'penulis' => 'Taylor Otwell',
            'penerbit' => 'Laravel LLC',
            'tahun_terbit' => 2025,
            'isbn' => '978-602-1234-56-7',
            'stok' => 10,
            'category_id' => $this->category->id,
        ]);
    }

    public function test_get_books_returns_paginated_resource(): void
    {
        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'judul',
                        'penulis',
                        'penerbit',
                        'tahun_terbit',
                        'isbn',
                        'stok',
                        'sampul',
                        'category' => ['id', 'nama_kategori'],
                        'created_at',
                        'updated_at',
                    ],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_get_books_with_filters(): void
    {
        $response = $this->getJson('/api/books?judul=Laravel');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.judul', 'Belajar Laravel 12');

        $emptyResponse = $this->getJson('/api/books?judul=NonExistentBook');
        $emptyResponse->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    public function test_get_book_detail_and_404(): void
    {
        $this->getJson('/api/books/' . $this->book->id)
            ->assertStatus(200)
            ->assertJsonPath('data.id', $this->book->id)
            ->assertJsonPath('data.judul', 'Belajar Laravel 12');

        $this->getJson('/api/books/999999')
            ->assertStatus(404);
    }

    public function test_store_book_validation_and_creation(): void
    {
        // Validation failure (422)
        $this->postJson('/api/books', [])
            ->assertStatus(422)
            ->assertJsonStructure(['message', 'errors']);

        // Success creation (201)
        $response = $this->postJson('/api/books', [
            'judul' => 'Mastering RESTful API',
            'penulis' => 'John Doe',
            'penerbit' => 'Tech Press',
            'tahun_terbit' => 2024,
            'isbn' => '978-602-000-000-1',
            'stok' => 5,
            'category_id' => $this->category->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.judul', 'Mastering RESTful API')
            ->assertJsonPath('data.category.id', $this->category->id);

        $bookId = $response->json('data.id');

        // Update (200)
        $this->putJson('/api/books/' . $bookId, [
            'judul' => 'Mastering RESTful API (Second Edition)',
            'penulis' => 'John Doe',
            'penerbit' => 'Tech Press',
            'tahun_terbit' => 2024,
            'isbn' => '978-602-000-000-1',
            'stok' => 15,
            'category_id' => $this->category->id,
        ])->assertStatus(200)
            ->assertJsonPath('data.judul', 'Mastering RESTful API (Second Edition)')
            ->assertJsonPath('data.stok', 15);

        // Update validation failure (422)
        $this->putJson('/api/books/' . $bookId, [])
            ->assertStatus(422);

        // Delete (200)
        $this->deleteJson('/api/books/' . $bookId)
            ->assertStatus(200)
            ->assertJson(['message' => 'Buku berhasil dihapus.']);

        // Delete 404
        $this->deleteJson('/api/books/' . $bookId)
            ->assertStatus(404);
    }

    public function test_get_members_and_detail(): void
    {
        $this->getJson('/api/members')
            ->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonPath('data.0.nama', 'Ahmad Dahlan');

        $this->getJson('/api/members/' . $this->member->id)
            ->assertStatus(200)
            ->assertJsonPath('data.id', $this->member->id)
            ->assertJsonPath('data.nim', '3120600001');

        $this->getJson('/api/members/999999')
            ->assertStatus(404);
    }

    public function test_loans_endpoints(): void
    {
        $this->getJson('/api/loans')
            ->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta']);

        // Validation failure (422)
        $this->postJson('/api/loans', [])
            ->assertStatus(422)
            ->assertJsonStructure(['message', 'errors']);

        // Create loan (201)
        $response = $this->postJson('/api/loans', [
            'member_id' => $this->member->id,
            'user_id' => $this->user->id,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_kembali' => now()->addDays(7)->toDateString(),
            'book_ids' => [$this->book->id],
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.status', 'dipinjam')
            ->assertJsonPath('data.member.id', $this->member->id)
            ->assertJsonPath('data.petugas.id', $this->user->id)
            ->assertJsonPath('data.books.0.id', $this->book->id);

        $loanId = $response->json('data.id');

        // Return loan (200)
        $this->putJson("/api/loans/{$loanId}/kembalikan")
            ->assertStatus(200)
            ->assertJsonPath('data.status', 'dikembalikan')
            ->assertJsonPath('data.tanggal_dikembalikan', now()->toDateString());

        // Return non-existent loan (404)
        $this->putJson('/api/loans/999999/kembalikan')
            ->assertStatus(404);
    }

    public function test_stats_endpoint(): void
    {
        $response = $this->getJson('/api/stats');

        $response->assertStatus(200)
            ->assertJson([
                'total_buku' => 1,
                'total_anggota' => 1,
                'peminjaman_aktif' => 0,
            ]);
    }
}
