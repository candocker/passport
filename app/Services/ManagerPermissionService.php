<?php
declare(strict_types = 1);

namespace ModulePassport\Services;

use Illuminate\Hashing\BcryptHasher;
use Carbon\Carbon;

class ManagerPermissionService extends AbstractService
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
            return $this->throwExceoption(405, "用户{$name}已禁用");
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

    public function writeManagerLog($data, $dataPre = null)
    {
        $manager = request()->get('manager');
        if (empty($manager)) {
            return true;
        }
        $permission = request()->get('currentPermission');
        $rolePermissions = request()->get('rolePermissions');
        $infos = [
            'manager_id' => $manager['id'],
            'manager_name' => $manager['nickname'],
            'role' => $rolePermissions['roleStr'],
            'menu_code' => $permission['code'],
            'menu_name' => $permission['name'],
            'data' => json_encode($data),
            'data_pre' => !empty($dataPre) ? json_encode($dataPre) : '',
            'ip' => $this->resource->getIp(),
            'created_at' => date('Y-m-d H:i:s'),
        ];
        //print_r($infos);exit();

        $managerlogModel = $this->getModelObj('managerlog')->create($infos);

        return true;
    }
}
