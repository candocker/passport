<?php

declare(strict_types = 1);

namespace ModulePassport\Controllers;

use ModulePassport\Requests\SendCodeRequest;
use ModulePassport\Requests\ValidateCodeRequest;
use ModulePassport\Requests\ValidateCaptchaRequest;

class CommonController extends AbstractController
{
    public function captcha()
    {
        return captcha();
    }

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

    public function createResource()
    {
        $resources = $this->resource->getBaseCache('resource');
        $command = new \Framework\Baseapp\Commands\GenResourceCommand();
        $config = $this->config->get('local_params.resourcePath');
        $command->createResources($resources, $config);
        exit();
        //echo get_class($command);
        //print_R($resources);exit();
    }
}
