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

    public function getRole()
    {
        $roles = $this->roleManagers;
        $result = ['source' => [], 'show' => ''];
        foreach ($roles as $roleManager) {
            $result['source'][] = $roleManager->role['code'];
            $result['show'] .= ', ' . $roleManager->role['name'];
        }
        return $result;
    }

    public function afterSave()
    {
        $request = request();
        $roles = $request->input('role');
        if (!is_null($roles)) {
            $model = $this->getModelObj('roleManager');
            $model->where('manager_id', $this->id)->delete();
            foreach ($roles as $role) {
                $model->createRecord($role, $this);
            }
        }

        return true;
    }
}
