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

        $userName = $user['name'] ?? $user['userName'];
        $enable = $user->checkEnable();
        if (!$enable) {
            return $this->resource->throwExceoption(405, "用户{$userName}已禁用");
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

    public function updateResource()
    {
        $infos = $this->getModelObj('resource')->get();
        $datas = $tDatas = [];
        foreach ($infos as $info) {
            $datas[$info['code']] = $info->toArray();
        }
        $tInfos = \DB::select("SELECT * FROM `liuliubak`.`wp_auth_resource` ");
        foreach ($tInfos as $tInfo) {
            $tDatas[$tInfo->code] = (array) $tInfo;
        }

        $dCodes = '';
        foreach ($tDatas as $tCode => $tData) {
            if (!in_array($tCode, array_keys($datas))) {
                echo 'nnnn-' . $tCode;
                print_r($tData);
            }
        }

        $aCodes = '';
        $uSql = '';
        foreach ($datas as $sCode => $sData) {
            if (!in_array($sCode, array_keys($tDatas))) {
                //print_r($sData);
                $aCodes .= "'{$sCode}',";
                continue;
            }
            $tData = $tDatas[$sCode];
            $elems = ['controller', 'request', 'service', 'repository', 'model', 'resource', 'collection'];
            $uStr = '';
            foreach ($elems as $elem) {
                if ($sData[$elem] != $tData[$elem]) {
                    $uStr .= "`{$elem}` = '{$sData[$elem]}',";
                }
            }
            if ($uStr) {
                $uStr = trim($uStr, ',');
                $uSql .= "UPDATE `wp_auth_resource` SET {$uStr} WHERE `code` = '{$sCode}';\n";
            }
        }
        echo $uSql;

        $aCodes = trim($aCodes, ',');
        $aSql = "SELECT * FROM `wp_auth_resource` WHERE `code` IN ({$aCodes});";
        echo $aSql;

        exit();
        print_r($tDatas);exit();
    }

    public function updatePermission()
    {
        $infos = $this->getModelObj('permission')->get();
        $datas = $tDatas = [];
        foreach ($infos as $info) {
            $datas[$info['code']] = $info->toArray();
        }
        $tInfos = \DB::select("SELECT * FROM `liuliubak`.`wp_auth_permission` ");
        foreach ($tInfos as $tInfo) {
            $tDatas[$tInfo->code] = (array) $tInfo;
        }

        $dCodes = '';
        $dIgnores = ['double6_word-point_delete'];
        foreach ($tDatas as $tCode => $tData) {
            if (!in_array($tCode, array_keys($datas)) && !in_array($tCode, $dIgnores)) {
                //echo 'nnnn-' . $tCode;
                //print_r($tData);
                $dCodes .= "'{$tCode}',";
            }
        }
        $dCodes = trim($dCodes, ',');
        $dSql = "DELETE FROM `wp_auth_role_permission` WHERE `permission_code` IN ({$dCodes});\n";
        $dSql .= "DELETE FROM `wp_auth_permission` WHERE `code` IN ({$dCodes});\n";
        echo $dSql;
        exit();

        $aCodes = '';
        $uSql = '';
        foreach ($datas as $sCode => $sData) {
            if (!in_array($sCode, array_keys($tDatas))) {
                //print_r($sData);
                $aCodes .= "'{$sCode}',";
                continue;
            }
            $tData = $tDatas[$sCode];
            $elems = ['name', 'code', 'parent_code', 'resource_code', 'controller', 'route', 'route_path', 'action', 'method', 'display'];
            $uStr = '';
            foreach ($elems as $elem) {
                if ($sData[$elem] != $tData[$elem]) {
                    $uStr .= "`{$elem}` = '{$sData[$elem]}',";
                }
            }
            if ($uStr) {
                $uStr = trim($uStr, ',');
                $uSql .= "UPDATE `wp_auth_permission` SET {$uStr} WHERE `code` = '{$sCode}';\n";
            }
        }
        echo $uSql;

        $aCodes = trim($aCodes, ',');
        $aSql = "SELECT * FROM `wp_auth_permission` WHERE `code` IN ({$aCodes});";
        echo $aSql;
        echo "\n";

        exit();
        print_r($tDatas);exit();
    }
}
