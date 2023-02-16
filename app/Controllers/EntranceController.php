<?php

declare(strict_types = 1);

namespace ModulePassport\Controllers;

use Framework\Baseapp\Services\EasysmsService;

class EntranceController extends AbstractController
{
    public function __construct()
    {
        parent::__construct();
        $this->easysmsService = new EasysmsService();
    }

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
    public function managerToken()
    {
        $request = $this->getPointRequest('managerToken');

        $service = $this->getServiceObj('managerPermission');
        $inputs = $request->all();
        $type = $params['type'];

        $username = $type == 'mobile' ? $inputs['mobile'] : $inputs['name'];
        $user = $service->getUser($username);
        $addUser = $params['addUser'] ?? false;
        if (empty($user) && $type == 'mobile' && $addUser) {
            $user = $service->addUserByInput($inputs);
        }
        $password = $type == 'mobile' ? false : ($inputs['password'] ?? '');
        $user = $service->formatLoginUser($user, $password);

        $repository = $this->getRepositoryObj('user');
        $datas['user'] = $repository->getUserData($user);
        $datas['access_token'] = $this->_getToken('login', $user);
        $datas['expires_in'] = $this->_getTTL();
        return $this->success($datas);
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

        $username = $type == 'mobile' ? $inputs['mobile'] : $inputs['name'];
        $user = $service->getUser($username);
        $addUser = $params['addUser'] ?? false;
        if (empty($user) && $type == 'mobile' && $addUser) {
            $user = $service->addUserByInput($inputs);
        }
        $password = $type == 'mobile' ? false : ($inputs['password'] ?? '');
        $user = $service->formatLoginUser($user, $password);

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
     * @group auth登录模块
     *
     * logout退出登录
     *
     * @return \Illuminate\Http\JsonResponse
     * @response {
     *   "code": 200,
     *   "message": "您已成功退出登录"
     * }
     */
    public function logout()
    {
        auth('api')->logout();
        return responseJsonHttp(200, '您已成功退出登录');
    }

    /**
     * Refresh a token.
     * 刷新token，如果开启黑名单，以前的token便会失效。
     * 值得注意的是用上面的getToken再获取一次Token并不算做刷新，两次获得的Token是并行的，即两个都可用。
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->success([
            'access_token' => $this->_getToken('refresh'),
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    protected function _getToken($type, $user = null)
    {
        if ($type == 'refresh') {
            return auth('api')->refresh();
        }
        $token = auth('api')->login($user);
        return $token;
    }

    protected function _getTTL()
    {
        return auth('api')->factory()->getTTL() * 60;
    }
}
