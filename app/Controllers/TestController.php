<?php

declare(strict_types = 1);

namespace ModulePassport\Controllers;

class TestController extends AbstractController
{
    public function test()
    {
        $request = $this->request;
        $inTest = config('app.inTest');
        if (empty($inTest)) {
            return $this->error(400, '非法请求');
        }
        $method = ucfirst($request->input('method', ''));
        $method = "_test{$method}";
        $this->$method($request);
    }

    protected function _testCache()
    {
        $repository = $this->getRepositoryObj('region');
        //$repository->setPointCaches('region');
        $datas = $repository->getPointCaches('permission');
        print_R($datas);
        $service = $this->getServiceObj('redis');
        $redis = $service->setRedis('common');

        print_r(get_class($redis));

    }

    public function _testOss($action)
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

    public function _test()
    {
        //exit();
    }
}
