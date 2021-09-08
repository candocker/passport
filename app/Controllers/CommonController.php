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
use ModulePassport\Requests\ValidateCaptchaRequest;

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
