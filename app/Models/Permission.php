<?php

declare(strict_types = 1);

namespace ModulePassport\Models;

class Permission extends AbstractModel
{
    public $incrementing = false;
    protected $primaryKey = 'code';

    protected $table = 'auth_permission';

    public function parentInfo()
    {
        return $this->hasOne(Permission::class, 'code', 'parent_code');
    }

    public function dealDeleting()
    {
        $this->getModelObj('rolePermission')->where('permission_code', $this->code)->delete();

        $this->getRepositoryObj('permission')->cacheRouteDatas();
        return $this;
    }
}
