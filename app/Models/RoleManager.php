<?php

declare(strict_types = 1);

namespace ModulePassport\Models;

class RoleManager extends AbstractModel
{
    protected $table = 'auth_role_manager';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function role()
    {
        return $this->hasOne(Role::class, 'code', 'role_code');
    }

    public function createRecord($role, $manager)
    {
        $data = ['role_code' => $role, 'manager_id' => $manager['id'], 'created_at' => date('Y-m-d H:i:s')];
        return $this->create($data);
    }
}
