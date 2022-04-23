<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'path',
        'target'
    ];

    protected function getPathAttribute(): string
    {
        return url($this->attributes['path']);
    }

    /**
     * Get the user that owns the urls.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
