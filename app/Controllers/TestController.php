<?php

declare(strict_types = 1);

namespace ModulePassport\Controllers;

use Illuminate\Support\Str;

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

    public function _testCheckResource($request)
    {
        $config = $this->config->get('local_params.resourcePath');
        $dataConfig = config('database');
        $command = new \Framework\Baseapp\Commands\GenResourceCommand();
        $command->checkResource($dataConfig['connections'], $config);
        print_r($config);exit();
    }

    public function _testResource($request)
    {
        \DB::update("TRUNCATE `wp_auth_role_permission`;");
        \DB::update("REPLACE INTO `wp_auth_role_permission`(`role_code`, `permission_code`, `created_at`) SELECT 'admin', `code`, `created_at` FROM `wp_auth_permission` WHERE 1 ;");
        $this->getRepositoryObj('resource')->cacheResourceDatas();
        $this->getRepositoryObj('permission')->cacheRouteDatas();
        $params = $request->all();
        $resources = $this->resource->getBaseCache('resource');
        $command = new \Framework\Baseapp\Commands\GenResourceCommand();
        $config = $this->config->get('local_params.resourcePath');
        $command->createResources($resources, $config);
        exit();
        //echo get_class($command);
        //print_R($resources);exit();
    }

    protected function _testCache($request)
    {
        $repository = $this->getRepositoryObj('region');
        //$repository->setPointCaches('region');
        $datas = $repository->getPointCaches('permission');
        print_R($datas);
        $service = $this->getServiceObj('redis');
        $redis = $service->setRedis('common');

        print_r(get_class($redis));

    }

    public function _testAttachmentInfo()
    {
        $model = $this->getModelObj('attachment');
        //$infos = $model->where(['system' => 'ossfree'])->where('extfield', '>', '0')->whereIn('path_id', [1414, 1417])->get();
        $infos = $model->where(['system' => 'ossfree'])->where('extfield', '1')->whereIn('path_id', [1415, 1418, 1419, 1420, 1421, 1422])->get();
        $sql = '';
        foreach ($infos as $info) {
            $sql .= $info->dispatchInfo('book');
        }
        echo "\n" . $sql;
        exit();
    }

    public function _testOss($request)
    {
        $action = $request->input('param');
        $service = $this->getServiceObj('oss');

        //$r = $service->dealDirectory();
        //$r = $service->fileData('d');
        //$r = $service->dealPut('book/test.jpg', 'a');
        //$r = $service->getUrl('a');
        //print_r($r);

        //$r = $service->checkOssFiles();exit();
        //$r = $service->checkOssRemote();exit();
        //$r = $service->checkInfoDatas();exit();
        //$r = $service->putFile(['a' => 'b'], 'book/' . Str::uuid() . '.jpg', 'a');
        //$service->dealOldAttachment();

        $model = $this->getModelObj('attachment');
        /*$aDatas = $model->where(['system' => 'ossfree', 'path_id' => 1414])->get();
        $sql = '';
        foreach ($aDatas as $aData) {
            $source = $aData['filepath'];
            $target = str_replace('figure/american/', 'figure/american_president/', $source);
            echo "<a href='http://ossfile.canliang.wang/{$aData['filepath']}' target='_blank'>yyyyy<a>-" . $aData['name'] . '===' . $aData['filepath'] . '==' . $target . "<img src='http://ossfile.canliang.wang/{$aData['filepath']}' /><br />";
            $aData->filepath = $target;
            //$aData->path_id = 1415;
            $aData->save();
            $r = $service->dealFile('move', $source, $target);
            var_dump($r);
        }
        echo "\n";
        exit();*/

        $service = $this->getServiceObj('oss');
        echo $action;exit();
    }

    public function _test()
    {
        //exit();
    }
}
