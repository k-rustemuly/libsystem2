<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class BookDistributionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_distribution_template_id',
        'book_id',
        'count'
    ];

    public function bookDistributionTemplate(): BelongsTo
    {
        return $this->belongsTo(BookDistributionTemplate::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

}
