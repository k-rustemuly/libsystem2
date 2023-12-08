<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookDistributionTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'title',
        'book_count'
    ];

    public function bookDistributionItems(): HasMany
    {
        return $this->hasMany(BookDistributionItem::class);
    }
}
