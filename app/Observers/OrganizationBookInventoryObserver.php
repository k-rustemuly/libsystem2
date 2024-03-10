<?php

namespace App\Observers;

use App\Models\OrganizationBookInventory;

class OrganizationBookInventoryObserver
{
    public function deleted(OrganizationBookInventory $organizationBookInventory)
    {
        $receivedBook = $organizationBookInventory->receivedBook;
        $receivedBook->count-=1;
        $receivedBook->save();
    }
}
