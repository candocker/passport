<?php

declare(strict_types = 1);

namespace ModulePassport\Models;

class RolePermission extends AbstractModel
{
    public $incrementing = false;
    //protected $primaryKey = 'role_code';
    protected $table = 'auth_role_permission';
    protected $fillable = ['role_code', 'permission_code','created_at'];
    public $timestamps = false;

    public function role()
    {
        return $this->hasOne(Role::class, 'role_code', 'code');
    }

    public function permission()
    {
        return $this->hasOne(Permission::class, 'code', 'permission_code');
    }
}
