<?php

namespace App\Observers;

use App\Models\OrganizationBookInventory;
use App\Models\OrganizationBookTransaction;

class OrganizationBookTransactionObserver
{
    public function created(OrganizationBookTransaction $organizationBookTransaction)
    {
        $organizationBookInventory = new OrganizationBookInventory(['organization_id' => $organizationBookTransaction->getPrefix()]);
        $organizationBookInventory = $organizationBookInventory->find($organizationBookTransaction->inventory_id);
        $organizationBookInventory->transaction_id = $organizationBookTransaction->id;
        $organizationBookInventory->save();
    }
}
