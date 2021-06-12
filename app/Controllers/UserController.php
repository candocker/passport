<?php

declare(strict_types = 1);

namespace ModulePassport\Controllers;

use Illuminate\Hashing\BcryptHasher;

class UserController extends AbstractController
{

	/**
	 * 获取当前登录用户信息
	 */
	public function myinfo()
	{
        try {
		    $result = $this->getJwt->getParserData();
			$userId = isset($result['user_id']) ? $result['user_id'] : 0;
			if (empty($userId)) {
				return $this->helper->error(401, '当前登录用户信息有误');
			}

            $repository = $this->getRepositoryObj();
			$user = $repository->getUser($userId);
			if (empty($user)) {
				return $this->helper->error(401, '当前登录用户信息有误1');
			}
			return ['code' => 200, 'message' => 'success', 'data' => $repository->getUserData($user)];

        } catch (\Exception $e) {
            throw $e;
			echo 'error jwt';
			return ;
        }
        return $this->helper->error(401, '您没有权限');
	}

    protected function getJwt()
    {
        return null;
    }

    //get
    /*public function index()
    {
        $params = $this->request->all();
        $pageSize = $this->request->input('per_page', 15);
        $list = Model\User::getList($params, (int) $pageSize);
        return $list;
    }

    //post create
    public function store(Request\UserRequest $request)
    {
        $data = $request->all();
        if (empty($data['password'])) {
            throw new BusinessException(400, '请填写密码');
        }
        $data['password'] = $this->hash->make($data['password']);
        $user = Model\User::create($data);
        return $user;
    }

    // get
    public function show($id)
    {
        $user = Model\User::where('user_id', '<>', config('app.super_admin'))->find($id);
        if (!$user) {
            throw new BusinessException(400, "用户ID：{$id}不存在");
        }
        return $user;
    }

    // put
    public function update(Request\UserRequest $request, $id)
    {
        $data = $request->all();
        $user = Model\User::where('user_id', '<>', config('app.super_admin'))->find($id);
        if (!$user) {
            throw new BusinessException(400, "用户ID：{$id}不存在");
        }
        if (isset($data['name'])) {
            unset($data['name']);
        }
        if (isset($data['password']) && empty($data['password'])) {
            unset($data['password']);
        } elseif (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = $this->hash->make($data['password']);
        }
        $user->update($data);
        return $user;
    }

    // delete
    public function destroy($id)
    {
        
    }

    public function roles($id)
    {
        $roles = $this->request->input('roles', []);
        $model = Model\User::where('user_id', '<>', config('app.super_admin'))->find($id);
        if (!$model) {
            throw new BusinessException(400, "用户ID：{$id}不存在");
        }
        $model->syncRoles($roles);
        return $model;
    }*/

}
