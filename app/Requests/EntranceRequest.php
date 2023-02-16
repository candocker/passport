<?php

declare(strict_types = 1);

namespace ModulePassport\Requests;

class EntranceRequest extends AbstractRequest
{

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $scene = $this->getScene();
        switch ($scene) {
        case 'signin':
            return [
                'mobile' => 'required|mobile|exists:user,mobile',
                'code' => ['bail', 'required'],
                'type' => ['bail', 'required'],
            ];
        case 'signupin':
            return [
                'mobile' => 'required|mobile',
                'code' => ['bail', 'required'],
                'type' => ['bail', 'required'],
            ];
        case 'signup':
            return [
                'mobile' => ['bail', 'unique:user'],
                'code' => ['bail', 'required'],
                'type' => ['bail', 'required'],
                'password' => ['bail', 'filled', 'alpha_dash', 'between:6,20'],
                'name' => ['bail', 'filled', 'string', 'between:2,30', 'unique:user'],
                'nickname' => ['bail', 'filled', 'string', 'between:2,30'],
            ];
        case 'managerToken':
            return [
                'name' => ['bail', 'required'],
                'password' => ['bail', 'required']
            ];
        case 'token':
            return [
                'name' => ['bail', 'required'],
                'password' => ['bail', 'required']
            ];
        }
    }

    public function attributes(): array
    {
        return [
            'name' => '用户名',
            'password' => '密码'
        ];
    }

    public function messages(): array
    {
        $scene = $this->getScene();
        switch ($scene) {
        case 'signin':
            return [
                'mobile' => '手机号有误或者用户不存在',
                'mobile.exists' => '手机号有误或者用户不存在',
            ];
        case 'signup':
            return [
                'mobile' => '手机号有误或者用户不存在',
                'mobile.unique' => '手机号已存在',
            ];
        }
        return [
            'name.required' => '请填写用户名',
            'password.required' => '请填写密码',
        ];
    }
}
