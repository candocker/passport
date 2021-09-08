<?php

declare(strict_types = 1);

namespace ModulePassport\Controllers;

use Phper666\JWTAuth\JWT;
use Hyperf\Di\Annotation\Inject;
use Framework\Baseapp\Services\EasysmsService;

class EntranceController extends AbstractController
{
    /**
     * @Inject
     * @var JWT
     */
    protected $jwt;

    /**
     * @Inject
     * @var EasysmsService
     */
    protected $easysmsService;

    public function signup()
    {
        $request = $this->getPointRequest('signup');
        $checkCode = $this->easysmsService->validateCode($request->all());
        return $this->_token($request, ['type' => 'mobile', 'addUser' => true]);
    }

    public function signupin()
    {
        $request = $this->getPointRequest('signupin');
        $checkCode = $this->easysmsService->validateCode($request->all());
        return $this->_token($request, ['type' => 'mobile', 'addUser' => true]);
    }

    public function signin()
    {
        $request = $this->getPointRequest('signin');
        $checkCode = $this->easysmsService->validateCode($request->all());
        return $this->_token($request, ['type' => 'mobile']);
    }

	/**
 	 * 获取token
	 */
    public function token()
    {
        $request = $this->getPointRequest('token');
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
        $datas['access_token'] = $this->_getToken('login', $user);
        $datas['expires_in'] = $this->_getTTL();
        return $this->success($datas);
    }

    public function myRoutes()
    {
        $request = $this->request;
        $rolePermissions = $request->get('rolePermissions');
        return $this->success($rolePermissions);
    }

	/**
 	 * 刷新token
	 */
    public function refreshToken()
    {
        $token = $this->jwt->refreshToken();
        return ['access_token' => (string) $token, 'expires_in' => $this->jwt->getTTL()];
    }

	/**
 	 * 退出登录
	 */
    public function logout()
    {
        try {
            $this->jwt->logout();
        } catch (\Exception $e) {
            return $this->helper->error(410, 'Token有误');
        }
        return $this->helper->success('success');
    }

    protected function _getToken($user)
    {
        $token = (string) $this->jwt->getToken(['user_id' => $user->id]);
        return $token;
    }

    protected function _getTTL()
    {
        return $this->jwt->getTTL();
    }
}
