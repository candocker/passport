<?php

declare(strict_types = 1);

namespace ModulePassport\Controllers;

class RoleController extends AbstractController
{

    public function authority()
    {
        $repository = $this->getRepositoryObj();
        $request = $this->getPointRequest('', $repository);
        $code = $request->input('code', '');
        $info = $repository->find($code);
        if (empty($info)) {
            $this->throwException(422, '参数有误');
        }
        $permissions = $request->input('permissions');
        if (empty($permissions)) {
            $this->throwException(422, '参数有误1');
        }

        $repository->dealAuthority($info, $permissions);
        return $this->success(['message' => "为'{$info['name']}'设置权限成功"]);
    }
}
