<?php

namespace App\Models;

use App\Traits\CustomModelRealation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationReader extends Model
{
    use HasFactory;
    use CustomModelRealation;

    protected $fillable = [
        'organization_id',
        'user_id',
        'debt'
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPreviewNameAttribute()
    {
        return $this->user->name;
    }

    public function transactions()
    {
        return $this->customMorphMany(new OrganizationBookTransaction(['organization_id' => $this->organization_id]), 'recipientable');
    }
}
