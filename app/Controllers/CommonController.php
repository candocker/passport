<?php

declare(strict_types = 1);

namespace ModulePassport\Controllers;

use ModulePassport\Requests\SendCodeRequest;
use ModulePassport\Requests\ValidateCodeRequest;
use ModulePassport\Requests\ValidateCaptchaRequest;

class CommonController extends AbstractController
{
    public function validateCaptcha(ValidateCaptchaRequest $request)
    {
        return ['code' => 200, 'message' => 'OK'];
    }

    public function sendCode(SendCodeRequest $request)
    {
        $data = $request->all();
        return $this->getServiceObj('easysms')->sendCode($data);
    }

    public function validateCode(ValidateCodeRequest $request)
    {
        $data = $request->all();
        return $this->getServiceObj('easysms')->validateCode($data);
    }
}
