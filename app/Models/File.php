<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class File extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'extension',
        'fileable_type',
        'fileable_id',
        'mime_type',
        'pages',
        'path',
        'size',
        'visibility',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'extension' => 'string',
            'fileable_type' => 'string',
            'fileable_id' => 'integer',
            'mime_type' => 'string',
            'pages' => 'integer',
            'path' => 'string',
            'size' => 'integer',
            'visibility' => 'string',
        ];
    }
}
