<?php

declare(strict_types = 1);

namespace ModulePassport\Requests;

class ValidateCaptchaRequest extends AbstractRequest
{

    public function rules(): array
    {
        return [
            'captcha' => 'required|captcha',
        ];
    }

    public function attributes(): array
    {
        return [
            'captcha' => '图片验证码',
        ];
    }

    public function messages(): array
    {
        return [
            'captcha.reqired' => '验证码不能为空',
            'captcha.captcha' => '验证码有误',
        ];
    }
}
