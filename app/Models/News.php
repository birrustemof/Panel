<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // ƏLAVƏ EDİN

class News extends Model
{
    use HasFactory, SoftDeletes; // SOFTDELETES ƏLAVƏ EDİN

    protected $fillable = [
        'title',
        'text',
        'image',
        'author'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
