<?php

declare(strict_types = 1);

namespace ModulePassport\Requests;

use Swoolecan\Foundation\Helpers\CommonTool;

class ManagerRequest extends AbstractRequest
{
    protected function _addRule()
    {
        return [
            'name' => ['bail', 'string', 'required', 'between:2,60'],
            'nickname' => ['bail', 'string', 'required', 'unique:auth_manager,nickname', 'between:2,60'],
            //'user_id' => ['bail', 'required', 'unique:auth_manager,user_id', 'exists:user,id'],
            'status' => [$this->_getKeyValues('status')],
            //'mobile' => 'nullable|mobile',
            'password' => 'nullable|min:6|max:20|confirmed:confirm_password',
        ];
    }

    protected function _updateRule()
    {
        return [
            //'nickname' => ['string|between:6,20'],
            'name' => ['bail', 'filled', 'string', 'between:2,60'],
            'nickname' => ['bail', 'filled', 'string', 'unique:auth_manager,nickname', 'between:3,20'],
            //'mobile' => 'filled|mobile',
            'status' => [$this->_getKeyValues('status')],
            'password' => 'filled|min:6|max:20|confirmed:confirm_password',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '账号'
        ];
    }

    public function _messages(): array
    {
        return [
            'name.required' => '请填写账号',
            'name.between' => '账号长度为2-60字符串',
            'name.unique' => '账号已存在',
            'nickname.unique' => '名字已存在',
        ];
    }

    public function filterDirtyData($data)
    {
        $userPermission = $this->getRepository()->getServiceObj('userPermission');
        if (isset($data['name'])) {
            $user = $userPermission->getUserData($data);
            $data['user_id'] = $user['id'];
        }
        foreach (['mobile', 'role', 'name', 'password', 'password_confirmation', 'gender', 'birthday'] as $field) {
            if (isset($data[$field])) {
                unset($data[$field]);
                $this->allowEmpty = true;
            }
        }
        return $data;
    }

    public function checkInfo($info, $data)
    {
        $userPermission = $this->getRepository()->getServiceObj('userPermission');
        $user = $userPermission->getUserData($data, $info);
        return true;
    }
}
