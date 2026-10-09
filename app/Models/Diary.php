<?php

namespace App\Models;

use Database\Factories\DiaryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    /** @use HasFactory<DiaryFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'cover_color',
    ];

    public function pages()
    {
        return $this->hasMany(Page::class)->orderBy('page_number');
    }
}
