<?php
declare(strict_types = 1);

namespace ModulePassport\Repositories;

class RoleRepository extends AbstractRepository
{
    protected function _sceneFields()
    {
        return [
            'list' => ['code', 'name', 'description', 'updated_at'],
            'view' => ['code', 'name', 'description', 'permission_data', 'updated_at'],
            'listSearch' => ['code', 'name', 'updated_at'],
            'add' => ['code', 'name', 'description'],
            'update' => ['name', 'description'],
        ];
    }

    public function getShowFields()
    {
        return [
            'permission_data' => ['valueType' => 'callback', 'method' => 'permissionData'],
        ];
    }

    public function permissionData($model, $field)
    {
        $permission = $this->getRepositoryObj('permission');
        $trees = $permission->getTreeInfos();
        $datas = $model->permissions;//->keyBy('permission_code');
        $checked = [];
        foreach ($datas as $data) {
            if (empty($data->permission['parent_code']) || empty($data->permission->parentInfo['parent_code'])) {
                continue;
            }
            $checked[] = $data->permission['code'];
        }
        //$checked = $datas->keys();
        return ['checked' => $checked, 'trees' => $trees];
        //return $permission->getTreeInfos($model->getFormatPermissions());
    }

    public function dealAuthority($info, $permissions)
    {
        $olds = $info->permissions->keyBy('permission_code');
        $exists = $olds->keys()->toArray();
        $rolePermission = $this->getModelObj('rolePermission');
        $pModel = $this->getModelObj('permission');
        $formatedPermissions = [];
        foreach ($permissions as $newPermission) {
            $permission = $pModel->where(['code' => $newPermission])->first();
            if (empty($permission)) {
                continue;
            }
            $formatedPermissions[] = $newPermission;
            if (!empty($permission->parentInfo)) {
                $formatedPermissions[] = $permission->parentInfo['parent_code'];
                $formatedPermissions[] = $permission->parentInfo['code'];
            }
        }
        $formatedPermissions = array_filter(array_unique($formatedPermissions));
        foreach ($formatedPermissions as $fPermission) {
            if (!in_array($fPermission, $exists)) {
                $newData = [
                    'role_code' => $info->code,
                    'permission_code' => $fPermission,
                    'created_at' => date('Y-m-d H:i:s', time()),
                ];
                $rolePermission->create($newData);
            }
        }

        $noPermissions = [];
        foreach ($olds as $old) {
            //echo get_class($old) . '=====' . $old->permission_code;
            if (!in_array($old->permission_code, $formatedPermissions)) {
                $noPermissions[] = $old->permission_code;
            }
        }
        if (!empty($noPermissions)) {
            $rolePermission->where('role_code', $info->code)->whereIn('permission_code', $noPermissions)->delete();
        }
        return true;
    }
}
