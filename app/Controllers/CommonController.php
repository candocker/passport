<?php

declare(strict_types = 1);

namespace ModulePassport\Controllers;

use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpMessage\Cookie\Cookie;
use Framework\Baseapp\Services\EasysmsService;
use ModulePassport\Requests\SendCodeRequest;
use ModulePassport\Requests\ValidateCodeRequest;

class CommonController extends AbstractController
{
    /**
     * @Inject
     * @var EasysmsService
     */
    protected $easysmsService;

    //验证码
    public function captcha()
    {
        $length = $this->request->input('length', 4);
        $width = $this->request->input('width', 80);
        $height = $this->request->input('height', 35);
        $phraseBuilder = new PhraseBuilder($length);
        $builder = new CaptchaBuilder(null, $phraseBuilder);
        $builder->build($width, $height);
        $phrase = $builder->getPhrase();
        $captchaId = uniqid();
        $this->cache->set($captchaId, $phrase, 300);
        $cookie = new Cookie('captcha', $captchaId);
        $output = $builder->get();
        return $this->response
                        ->withCookie($cookie)
                        ->withAddedHeader('content-type', 'image/jpeg')
                        ->withBody(new SwooleStream($output));
    }

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
