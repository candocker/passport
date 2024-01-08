<?php

declare(strict_types = 1);

namespace ModulePassport\Requests;

use Swoolecan\Foundation\Helpers\CommonTool;

class UserRequest extends AbstractRequest
{

    public function _addRule()
    {
        return [
            'name' => [
                'bail',
                'required',
                'string',
                'min:3',
                'max:60',
                $this->getRule()->unique('user')->ignore($this->routeParam('id', 0), 'user_id'),
            ],
            'mobile' => [
                'bail',
                'required',
                'mobile',
                $this->getRule()->unique('user'),
            ],
            'password' => 'required|min:6|max:20|confirmed:confirm_password',
            'status' => ['required', $this->_getKeyValues('status')],
        ];
    }

    protected function _changePasswordRule()
    {
        return [
            'password_old' => 'bail|required',
            'password' => 'required|min:6|max:20|confirmed:confirm_password',
        ];
    }

    protected function _updateRule()
    {
        return [
            'name' => [
                'bail',
                'filled',
                'string',
                'min:3',
                'max:60',
                $this->getRule()->unique('user')->ignore($this->routeParam('id', 0), 'id'),
            ],
            'mobile' => [
                'bail',
                'filled',
                'mobile',
                $this->getRule()->unique('user')->ignore($this->routeParam('id', 0), 'mobile'),
            ],
            'password' => 'filled|min:6|max:20|confirmed:confirm_password',
            'status' => ['filled', $this->_getKeyValues('status')],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '用户名',
            'real_name' => '姓名'
        ];
    }

    public function filterDirtyData($data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = CommonTool::createPassword($data['password']);
            unset($data['password_confirmation']);
        }

        return $data;
    }
}
