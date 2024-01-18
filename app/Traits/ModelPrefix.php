<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Str;

trait ModelPrefix
{

    public function getPrefix()
    {
        return Str::of($this->getTable())->explode('_')->last();
    }
}
