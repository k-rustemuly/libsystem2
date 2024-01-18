<?php

namespace App\Observers;

use App\Models\OrganizationBook;
use App\Models\OrganizationBookInventory;

class OrganizationBookObserver
{
    public function saved(OrganizationBook $organizationBook)
    {
        $original = $organizationBook->getOriginal();
        $oldCount = count($original) > 0 ? $original['count'] : 0;
        $count = $organizationBook->count - $oldCount;
        (new OrganizationBookInventory(['organization_id' => $organizationBook->organization_id]))->generate($organizationBook, (int) $count);
    }
}
