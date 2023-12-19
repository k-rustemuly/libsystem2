<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceivedBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'book_id',
        'book_storage_type_id',
        'year',
        'count'
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function bookStorageType(): BelongsTo
    {
        return $this->belongsTo(BookStorageType::class);
    }
}
