<?php

namespace App\Models;

use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;

    protected $fillable = [
        'diary_id',
        'page_number',
        'song_title',
        'audio_file',
        'layout_type',
        'note_title',
        'note_content',
    ];

    public function diary()
    {
        return $this->belongsTo(Diary::class);
    }

    public function items()
    {
        return $this->hasMany(PageItem::class)->orderBy('position');
    }
}
