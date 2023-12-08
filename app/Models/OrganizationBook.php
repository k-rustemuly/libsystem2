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

}
