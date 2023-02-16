<?php

declare(strict_types = 1);

namespace ModulePassport\Observers;

use ModulePassport\Models\Manager;

class ManagerObserver
{
    public function saved(Manager $model)
    {
        if (empty($model->ignoreObserver)) {
            $model->afterSave();
        }
        return true;
    }
}
