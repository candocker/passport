<?php
declare(strict_types = 1);

namespace ModulePassport\Services;

use Illuminate\Hashing\BcryptHasher;
use Carbon\Carbon;

class UserPermissionService extends AbstractService
{
    protected $hash;

    public function __construct()
    {
        parent::__construct();
        $this->hash = new BcryptHasher();
    }

    protected function pointRepository()
    {
        return false;
    }

    public function checkInput($inputs, $params)
    {
        $type = $params['type'];
        
    }

    public function getUserById($id)
    {
        $repository = $this->getRepositoryObj('user');
        $user = $repository->find($id);
        return $user;
    }

    public function getUser($inputs, $type, $addUser = false)
    {
        $repository = $this->getRepositoryObj('user');
        $field = $type == 'password' ? 'name' : 'mobile';
        $value = $type == 'password' ? $inputs['name'] : $inputs['mobile'];
        $user = $repository->findBy($field, $value);
        if (empty($user) && $type == 'mobile' && $addUser) {
            if (isset($inputs['password'])) {
                $inputs['password'] = $this->hash->make($inputs['password']);
            }
            $user = $repository->addUser($inputs);
        }

        if (empty($user) && $type == 'password') {
            $user = $repository->findBy('mobile', $value);
        }

        if (empty($user)) {
            return $this->resource->throwException(400, "用户{$value}不存在");
        }
        if ($type == 'password' && !$this->hash->check($inputs['password'], $user->password)) {
            return $this->resource->throwException(400, '用户名或者密码错误');
        }

        $enable = $user->checkEnable();
        if (!$enable) {
            return $this->throwExceoption(405, "用户{$name}已禁用");
        }
        $user->recordSignin(['last_ip' => $this->resource->getIp()]);

        return $user;
    }

    public function getManager($user, $record = true)
    {
        $repository = $this->getRepositoryObj("manager");
        $manager = $repository->findBy('user_id', $user->id);
        if (empty($manager)) {
            return $this->throwException(400, "用户{$user['name']}不是管理员");
        }

        $enable = $manager->checkEnable();
        if (!$enable) {
            return $this->throwException(405, "用户{$user['name']}管理权限已禁用");
        }
        $manager->recordSignin(['last_ip' => $this->resource->getIp()]);
        return $manager;
    }

    public function getRolePermissions($manager)
    {
        $roleManagers = $manager->roleManagers;
        $permissionRepository = $this->getRepositoryObj('permission');
        $datas = [];
        foreach ($roleManagers as $roleManager) {
            $datas['roles'][] = $roleManager['role_code'];
            $datas['roleDetails'][$roleManager['role_code']] = $roleManager->role;
            $datas['permissions'][$roleManager['role_code']] = $permissionRepository->getTreeInfos($roleManager->role->getFormatPermissions());
            //echo $roleManager->role['name'] . '==' . count($datas['permissions'][$roleManager['role_code']]) . '---------';

        }
        return $datas;
    }

    public function getPointPermission($routeCode)//app, $path, $method)
    {
        return true;
    }

    public function checkPermissionTo($permission, $rolePermissions)
    {
        //return false;
        return true;
    }
}
