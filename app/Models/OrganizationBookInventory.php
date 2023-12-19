<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationBookInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_book_id',
        'organization_id',
        'book_id',
        'num',
        'code'
    ];

    public static function generate(OrganizationBook $organizationBook, int $count)
    {
        $num = 0;
        $last = self::where('organization_id', $organizationBook->organization_id)->orderBy('num', 'desc')->first();
        if($last) $num = $last->num;

        while($count > 0) {
            $num++;
            self::create([
                'organization_book_id' => $organizationBook->id,
                'organization_id' => $organizationBook->organization_id,
                'book_id' => $organizationBook->book_id,
                'num' => $num,
                'code' => sprintf('%05d-%010d', $organizationBook->organization_id, $num)
            ]);
            $count--;
        }
    }
}
