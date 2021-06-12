<?php

declare(strict_types = 1);

namespace ModulePassport\Models;

class RoleManager extends AbstractModel
{
    protected $table = 'auth_role_manager';

    public function role()
    {
        return $this->hasOne(Role::class, 'code', 'role_code');
    }
}
