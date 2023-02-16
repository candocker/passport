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
        return empty($user) ? false : $repository->getUserData($user);
    }

    public function getUser($username)
    {
        $repository = $this->getRepositoryObj('user');
        $user = $repository->findBy('mobile', $username);
        $user = empty($user) ? $repository->findBy('name', $username) : $user;
        return $user;
    }

    public function formatLoginUser($user, $password = false)
    {
        if (empty($user)) {
            return $this->resource->throwException(400, "用户不存在");
        }

        if ($password !== false && !$this->hash->check($password, $user->password)) {
            return $this->resource->throwException(400, '用户名或者密码错误');
        }

        $enable = $user->checkEnable();
        if (!$enable) {
            return $this->resource->throwExceoption(405, "用户{$name}已禁用");
        }
        $user->recordSignin(['last_ip' => $this->resource->getIp()]);

        return $user;
    }

    public function addUserByInput($inputs)
    {
        $repository = $this->getRepositoryObj('user');
        if (isset($inputs['password'])) {
            $inputs['password'] = $this->hash->make($inputs['password']);
        }
        return $repository->addUser($inputs);
    }

    public function getManager($user, $record = true)
    {
        $repository = $this->getRepositoryObj("manager");
        $manager = $repository->findBy('user_id', $user['id']);
        $userName = $user['name'] ?? $user['userName'];

        if (empty($manager)) {
            return $this->resource->throwException(400, "用户{$userName}不是管理员");
        }

        $enable = $manager->checkEnable();
        if (!$enable) {
            return $this->resource->throwException(405, "用户{$userName}管理权限已禁用");
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
            $permissions = $roleManager->role->getFormatPermissions();
            $role = $roleManager->role;
            $roleStr = $datas['roleStr'] ?? '';
            $roleStr .= $role['code'] . '/' . $role['name'] . '|';
            $datas['roles'][] = $roleManager['role_code'];
            $datas['roleStr'] = $roleStr;
            $datas['roleDetails'][$roleManager['role_code']] = $role;
            $datas['permissions'][$roleManager['role_code']] = $permissionRepository->getTreeInfos($permissions);
            $datas['basePermission'][$roleManager['role_code']] = $permissions;
            //echo $roleManager->role['name'] . '==' . count($datas['permissions'][$roleManager['role_code']]) . '---------';

        }
        return $datas;
    }

    /*public function getPointPermission($routeCode)//app, $path, $method)
    {
        //echo $routeCode;exit();
        return true;
    }*/

    public function checkPermissionTo($permission, $rolePermissions)
    {
        //return false;
        return true;
    }
}
