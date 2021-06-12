<?php

declare(strict_types = 1);

namespace ModulePassport\Controllers;

use Framework\Baseapp\Services\EasysmsService;

class EntranceController extends AbstractController
{
    public function signup()
    {
        $request = $this->getRequestObj('signup');
        $checkCode = $this->easysmsService->validateCode($request->all());
        return $this->_token($request, ['type' => 'mobile', 'addUser' => true]);
    }

    public function signupin()
    {
        $request = $this->getRequestObj('signupin');
        $checkCode = $this->easysmsService->validateCode($request->all());
        return $this->_token($request, ['type' => 'mobile', 'addUser' => true]);
    }

    public function signin()
    {
        $request = $this->getRequestObj('signupin');
        $checkCode = $this->easysmsService->validateCode($request->all());
        return $this->_token($request, ['type' => 'mobile']);
    }

	/**
 	 * 获取token
	 */
    public function token()
    {
        $request = $this->getRequestObj('token');
        return $this->_token($request, ['type' => 'password']);
    }

    protected function _token($request, $params)
    {
        $service = $this->getServiceObj('userPermission');
        $inputs = $request->all();
        $type = $params['type'];
        if ($type == 'mobile') {
            $service->checkInput($inputs, $params);
        }
        $datas = [];
        $addUser = $params['addUser'] ?? false;
        $user = $service->getUser($inputs, $type, $addUser);

        $repository = $this->getRepositoryObj('user');
        $datas['user'] = $repository->getUserData($user);
        $datas['access_token'] = $this->_getToken($user);
        $datas['expires_in'] = $this->_getTTL();
        return $this->success($datas);
    }

    public function myRoutes()
    {
        $request = $this->request;
        $rolePermissions = $request->getAttribute('rolePermissions');
        return $this->success($rolePermissions);
    }
}
