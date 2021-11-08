<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ListRow extends Model
{
    protected $table = 'lists_rows';

    protected $fillable = ['date', 'order'];

    public function list(): BelongsTo
    {
        return $this->belongsTo(Listing::class, 'list_id');
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
