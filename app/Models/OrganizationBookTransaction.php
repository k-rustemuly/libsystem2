<?php

namespace App\Models;

use App\Traits\ModelPrefix;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationBookTransaction extends Model
{
    use HasFactory;
    use ModelPrefix;

    protected $fillable = [
        'organization_book_id',
        'book_id',
        'inventory_id',
        'received_date',
        'return_date',
        'comment'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (isset($attributes['organization_id'])) {
            $this->setTable('organization_book_transactions_' . $attributes['organization_id']);
        }
    }

    public function recipientable()
    {
        return $this->morphTo();
    }

    public function organizationBook(): BelongsTo
    {
        return $this->belongsTo(OrganizationBook::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

}
