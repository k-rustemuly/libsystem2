<?php

namespace App\Observers;

use App\Models\OrganizationReader;

class OrganizationReaderObserver
{
    public function deleting(OrganizationReader $organizationReader)
    {
        if($organizationReader->transactions()->exists()) {
            return false;
        }
    }
}
