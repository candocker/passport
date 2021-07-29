<?php

declare(strict_types = 1);

namespace ModulePassport\Observers;

use ModulePassport\Models\Permission;

class PermissionObserver
{
    public function deleting(Permission $model)
    {
        return $model->dealDeleting();
    }

    public function saved(Permission $model)
    {
        $repository = $model->getRepositoryObj();
        $repository->cacheRouteDatas();
        return true;
    }

    public function deleted(Permission $model)
    {
        $repository = $model->getRepositoryObj();
        $repository->cacheResourceDatas();
        return true;
    }
}
