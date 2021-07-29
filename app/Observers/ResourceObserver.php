<?php

declare(strict_types = 1);

namespace ModulePassport\Observers;

use ModulePassport\Models\Resource;

class ResourceObserver
{
    public function deleting(Resource $model)
    {
        return $model->canDelete();
    }

    public function saved(Resource $model)
    {
        $repository = $model->getRepositoryObj();
        $repository->cacheResourceDatas();
        return true;
    }

    public function deleted(Resource $model)
    {
        $repository = $model->getRepositoryObj();
        $repository->cacheResourceDatas();
        return true;
    }
}
