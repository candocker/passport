<?php

declare(strict_types = 1);

namespace ModulePassport\Models;

class Role extends AbstractModel
{
    public $incrementing = false;
    protected $primaryKey = 'code';
    protected $table = 'auth_role';
    protected $fillable = ['name', 'code', 'description'];
    protected $useCacheBuilder = true;

    public function afterDeleted()
    {
        $this->getModelObj('roleManager')->where('role_code', $this->code)->delete();
        $this->getModelObj('rolePermission')->where('role_code', $this->code)->delete();
    }

    public function permissions()
    {
        return $this->hasMany(RolePermission::class, 'role_code', 'code');
    }

    public function getFormatPermissions($onlyKey = false)
    {
        $rPermissions = RolePermission::query()->with('permission')->where('role_code', $this->code)->get();
        
        $datas = [];
        foreach ($rPermissions as $rPermission){
            $datas[$rPermission['permission_code']] = $rPermission->permission;
        }
        if (!empty($datas)) {
            $tmps = collect($datas);
            $tmps = $tmps->sortByDesc('orderlist');
            $result = [];
            foreach ($tmps as $key => $tmp) {
                $result[$key] = $tmp;
            }
            $datas = $result;
        }

        if ($onlyKey) {
            return array_keys($datas);
        }
        return $datas;
    }
}
