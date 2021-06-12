<?php

declare(strict_types = 1);

namespace ModulePassport\Models;

use ModulePassport\Models\Traits\TraitUser;

class Manager extends AbstractModel
{
    use TraitUser;

    protected $table = 'auth_manager';
    protected $primaryKey = 'id';
    protected $guarded = ['created_at', 'last_login_at', 'updated_at'];
    protected $fillable = ['nickname', 'user_id', 'status', 'signin_num', 'signin_first_at', 'last_at', 'last_ip'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function checkEnable()
    {
        return $this->status == 1;
    }

    public function roleManagers()
    {
        return $this->hasMany(RoleManager::class, 'manager_id', 'id');
    }
}
