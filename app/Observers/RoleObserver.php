<?php

declare(strict_types = 1);

namespace ModulePassport\Observers;

use ModulePassport\Models\Role;

class RoleObserver
{
    public function deleted(Role $model)
    {
        $model->afterDeleted();
        return true;
    }
}
