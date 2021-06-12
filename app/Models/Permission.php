<?php

declare(strict_types = 1);

namespace ModulePassport\Models;

class Permission extends AbstractModel
{
    public $incrementing = false;
    protected $primaryKey = 'code';

    protected $table = 'auth_permission';

    public function _afterDeleted()
    {
        $this->getPointModel('rolePermission')->where('permission_code', $this->code)->delete();

        $this->resource->getObject('repository', 'permission')->cacheRouteDatas();
        return $this;
    }
}
