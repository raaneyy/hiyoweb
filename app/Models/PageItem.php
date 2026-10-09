<?php

namespace App\Models;

use Database\Factories\PageItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageItem extends Model
{
    /** @use HasFactory<PageItemFactory> */
    use HasFactory;

    protected $fillable = [
        'page_id',
        'media_type',
        'file_path',
        'caption',
        'position',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
