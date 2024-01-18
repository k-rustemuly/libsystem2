<?php

namespace App\Models;

use App\Traits\CustomModelRealation;
use App\Traits\ModelPrefix;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationBookInventory extends Model
{
    use HasFactory;
    use CustomModelRealation;
    use ModelPrefix;

    protected $fillable = [
        'organization_book_id',
        'book_id',
        'num',
        'subnum',
        'code'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (isset($attributes['organization_id'])) {
            $this->setTable('organization_book_inventories_' . $attributes['organization_id']);
        }
    }

    public function newInstance($attributes = [], $exists = false)
    {
        $model = parent::newInstance($attributes, $exists);

        return $model;
    }

    public function generate(OrganizationBook $organizationBook, int $count)
    {
        $num = 0;

        if($organizationBook->book_storage_type_id == BookStorageType::BASIC) {
            $last = $this->orderBy('num', 'desc')->first();
            if($last) {
                $num = $last->num;
            }

            while($count > 0) {
                $data = [
                    'organization_book_id' => $organizationBook->id,
                    'book_id' => $organizationBook->book_id,
                ];
                if (! is_null($num)) {
                    $num++;
                    $data = array_merge($data, [
                        'num' => $num,
                        'code' => sprintf('%05d-%010d', $organizationBook->organization_id, $num)
                    ]);
                }
                $this->create($data);
                $count--;
            }
        } else {
            $subnum = 0;
            $last = $this->where('organization_book_id', $organizationBook->id)->orderBy('subnum', 'desc')->first();
            if($last) {
                $num = $last->num;
                $subnum = $last->subnum;
            } else {
                $last = $this->orderBy('num', 'desc')->first();
                if($last) {
                    $num = $last->num;
                }
                $num++;
            }
            while($count > 0) {
                $data = [
                    'organization_book_id' => $organizationBook->id,
                    'book_id' => $organizationBook->book_id,
                    'num' => $num,
                ];
                if (! is_null($subnum)) {
                    $subnum++;
                    $data = array_merge($data, [
                        'subnum' => $subnum,
                        'code' => sprintf('%05d-%010d-%d', $organizationBook->organization_id, $num, $subnum)
                    ]);
                }
                $this->create($data);
                $count--;
            }
        }
    }

    public function transaction(): BelongsTo
    {
        return $this->customBelongsTo(new OrganizationBookTransaction(['organization_id' => $this->getPrefix()]), 'transaction_id');
    }
}
