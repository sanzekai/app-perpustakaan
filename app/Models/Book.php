<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// File: app/Models/Book.php
class Book extends Model
{
    protected $fillable = [
        'judul', 'penulis', 'penerbit', 'tahun_terbit',
        'isbn', 'stok', 'category_id', 'sampul',
    ];
}