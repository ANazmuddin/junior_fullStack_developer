<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Mendaftarkan kolom mana saja yang boleh diisi lewat Form/API
    protected $fillable = [
        'name',
        'stock',
        'price',
    ];
    // ----------------------------
}