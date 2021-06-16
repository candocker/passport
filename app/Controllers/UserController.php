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
        $user = $this->request->get('current_user');
        $repository = $this->getRepositoryObj();
		return ['code' => 200, 'message' => 'success', 'data' => $repository->getUserData($user)];
	}
}
