<?php

namespace App\Observers;

use App\Models\BookStorageType;
use App\Models\OrganizationBook;
use App\Models\OrganizationBookInventory;

class OrganizationBookObserver
{
    public function saved(OrganizationBook $organizationBook)
    {
        if($organizationBook->book_storage_type_id == BookStorageType::BASIC) {
            $original = $organizationBook->getOriginal();
            $oldCount = count($original) > 0 ? $original['count'] : 0;
            $count = $organizationBook->count - $oldCount;
            OrganizationBookInventory::generate($organizationBook, (int) $count);
        }
    }
}
