<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'book_id',
        'book_storage_type_id',
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

    public function receivedBooks()
    {
        return $this->hasMany(ReceivedBook::class, 'book_id', 'book_id');
    }

    public function inventory()
    {
        return $this->hasMany(OrganizationBookInventory::class, 'book_id', 'book_id');
    }
}
