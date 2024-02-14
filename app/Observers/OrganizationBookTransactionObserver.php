<?php

namespace App\Observers;

use App\Models\OrganizationBookInventory;
use App\Models\OrganizationBookTransaction;
use App\Models\OrganizationReader;

class OrganizationBookTransactionObserver
{
    public function created(OrganizationBookTransaction $organizationBookTransaction)
    {
        $organizationBookInventory = new OrganizationBookInventory(['organization_id' => $organizationBookTransaction->getPrefix()]);
        $organizationBookInventory = $organizationBookInventory->find($organizationBookTransaction->inventory_id);
        $organizationBookInventory->transaction_id = $organizationBookTransaction->id;
        $organizationBookInventory->save();

        if($organizationBookTransaction->recipientable instanceof OrganizationReader) {
            $reader = $organizationBookTransaction->recipientable;
            $reader->debt+=1;
            $reader->save();
        }
    }

    public function updated(OrganizationBookTransaction $organizationBookTransaction)
    {
        $original = $organizationBookTransaction->getOriginal();
        if(is_null($original['returned_date']) && !is_null($organizationBookTransaction->returned_date)) {
            $inventory = $organizationBookTransaction->inventory;
            $inventory->transaction_id = null;
            $inventory->save();

            if($organizationBookTransaction->recipientable instanceof OrganizationReader) {
                $reader = $organizationBookTransaction->recipientable;
                $reader->debt-=1;
                $reader->save();
            }
        }
    }
}
