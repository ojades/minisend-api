<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Templates extends BaseModel
{
    use HasFactory;
    protected $guarded = [];

    public static function getBySlug($slug)
    {
        return self::where('slug', $slug)->first();
    }
}
