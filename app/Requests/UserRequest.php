<?php

declare(strict_types = 1);

namespace ModulePassport\Requests;

class UserRequest extends AbstractRequest
{

    public function rules(): array
    {
        return [
            'name' => [
                'bail',
                'required',
                'alpha_dash',
                $this->getRule()->unique('user')->ignore($this->routeParam('id', 0), 'user_id'),
            ],
            'phone' => [
                'bail',
                'required',
                $this->getRule()->unique('user')->ignore($this->routeParam('id', 0), 'user_id'),
            ],
            'real_name' => 'required',
            'password' => 'sometimes|same:confirm_password',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '用户名',
            'real_name' => '姓名'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '用户名必填',
            'name.unique' => '用户名已存在',
            'name.alpha_dash' => '用户名只能包含字母和数字,破折号和下划线',
            'real_name.required' => '姓名必填',
            'password.same' => '两次输入的密码不一致',
        ];
    }

}
