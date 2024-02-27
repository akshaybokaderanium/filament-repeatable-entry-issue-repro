<?php

namespace App\Traits;

use App\Models\ConsentOption;

/**
 * Trait to dynamically inititalise withCount property from user defined
 */
trait UserCount
{
    public function initializeUserCount()
    {
        $this->withCount = ConsentOption::getAllUserTypes()
            ->pluck('relation')
            ->toArray();
    }
}
