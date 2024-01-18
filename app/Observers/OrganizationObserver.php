<?php

namespace App\Observers;

use App\Events\OrganizationCreated;
use App\Models\Organization;

class OrganizationObserver
{
    public function created(Organization $organization)
    {
        event(new OrganizationCreated($organization->id));
    }
}
