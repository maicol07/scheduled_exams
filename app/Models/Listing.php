<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Listing extends Model
{
    protected $table = 'lists';
    protected $fillable = ['name', 'description', 'price', 'user_id', 'category_id', 'image_path'];

    public function user(): HasOne
    {
        return $this->hasOne(Classroom::class);
    }
}
