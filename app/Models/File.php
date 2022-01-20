<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    const TEXT = "text";
    const PHOTO = "photo";
    const VIDEO = "video";

    protected $fillable = ['folder_id', 'name', 'type', 'size'];

    public function folder() {
        return $this->belongsTo(Folder::class);
    }
}
