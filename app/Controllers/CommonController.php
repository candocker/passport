<?php

declare(strict_types = 1);

namespace ModulePassport\Controllers;

use ModulePassport\Requests\SendCodeRequest;
use ModulePassport\Requests\ValidateCodeRequest;

class CommonController extends AbstractController
{

	public function actionCheckCaptcha()
	{
		return $this->checkCommon('captcha');
	}

	public function sendCode(SendCodeRequest $request)
	{
        $data = $request->all();
		return $this->getServiceObj('easysms')->sendCode($data);
		return $this->easysmsService->sendCode($data);
	}

	public function validateCode(ValidateCodeRequest $request)
	{
        $data = $request->all();
		return $this->easysmsService->validateCode($data);
	}

    protected function checkCommon($field)
    {
		$data = $this->_formatInput([$field]);
		return $this->getModel()->checkCommon($field, $data[$field]);
    }
}
