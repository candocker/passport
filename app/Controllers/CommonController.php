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
        echo get_class($command);
        print_R($resources);exit();
    }

    public function oss($action)
    {
        $service = $this->getServiceObj('oss');
        //$service->dealOldAttachment();

        $service = $this->getServiceObj('oss');
        //$r = $service->dealDirectory();
        //$r = $service->dealFileLists();
        //$r = $service->fileData('d');
        //$r = $service->dealPut('book/test.jpg', 'a');
        //$r = $service->getUrl('a');
        //print_r($r);
        echo $action;exit();
    }
}
