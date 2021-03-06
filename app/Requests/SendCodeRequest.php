<?php

declare(strict_types = 1);

namespace ModulePassport\Requests;

class SendCodeRequest extends AbstractRequest
{

    public function rules(): array
    {
        return [
            'mobile' => 'required|mobile',
            'type' => 'required',
            'template' => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'mobile' => '手机号',
            'type' => '验证码类型',
            'template' => '模板',
        ];
    }

    public function messages(): array
    {
        return [
            'mobile.reqired' => '手机号不能为空',
            'mobile.mobile' => '手机号格式有误',
            'type' => '类型不能为空',
            'template' => '模板不能为空',
        ];
    }
}
