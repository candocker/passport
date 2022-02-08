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

    public function _testOss($request)
    {
        $action = $request->input('param');
        $service = $this->getServiceObj('oss');
        //$r = $service->checkOssFiles();exit();
        //$r = $service->checkOssRemote();exit();
        //$r = $service->checkInfoDatas();exit();
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

        $path = '/data/htmlwww/filesys/culture/';
        $url = 'http://upfile.canliang.wang/culture/';
        $files = scandir($path);
        foreach ($files as $dir) {
            if (in_array($dir, ['.', '..'])) {
                continue;
            }
            if ($dir != 'figure') {
                continue;
            }
            $subFiles = scandir($path . '/' . $dir);
            foreach ($subFiles as $subFile) {
                if (in_array($subFile, ['.', '..'])) {
                    continue;
                }
                $fileUrl = $url . $dir . '/' . $subFile;
                $this->checkAttachment($subFile, $fileUrl);
                //echo "<a href='{$fileUrl}' target='_blank'>{$fileUrl}</a><br />";
            }
            //print_R($subFiles);
        }
        print_r($files);exit();

        $service = $this->getServiceObj('oss');
        //$r = $service->dealDirectory();
        //$r = $service->fileData('d');
        //$r = $service->dealPut('book/test.jpg', 'a');
        //$r = $service->getUrl('a');
        //print_r($r);
        echo $action;exit();
    }

    protected function checkAttachment($file, $fileUrl)
    {
        static $i = 1;
        $model = $this->getModelObj('attachment');
        $infoModel = $this->getModelObj('attachmentInfo');
        $info = $model->where(['filename' => $file])->first();
        $baseFile = substr($file, 0, strrpos($file, '.'));
        $baseFile = substr($baseFile, intval(strrpos($baseFile, '·')));
        $baseFile = str_replace('·', '', $baseFile);
        //echo $file . '-' . $baseFile;exit();
        $figureModel = $this->getModelObj('culture-figure');
        $figure = $figureModel->where('name', 'like', "%{$baseFile}%")->get();
        if ($info) {
            $attachmentInfo = $infoModel->where('attachment_id', $info['id'])->first();
            //if (empty($attachmentInfo)) {
            //echo "<a href='http://ossfile.canliang.wang/{$info['filepath']}' target='_blank'>yyyyy<a>-" . $info['name'] . '===' . $info['filepath'] . "<img src='http://ossfile.canliang.wang/{$info['filepath']}' width='200px' height='200px' />==<img src='{$fileUrl}' width='200px' height='200px' /><br />";
            $i++;
            //}
        } else {
            //$info = $model->where('name', 'like', "%{$baseFile}%")->first();
            if (empty($info)) {
            } else {
            //echo "<a href='http://ossfile.canliang.wang/{$info['filepath']}' target='_blank'>yyyyy<a>-" . $info['name'] . '===' . $info['filepath'] . "<img src='http://ossfile.canliang.wang/{$info['filepath']}' width='200px' height='200px' />==<img src='{$fileUrl}' width='200px' height='200px' /><br />";
            //echo $i . '--' . "<a href='{$fileUrl}' target='_blank'>{$fileUrl}</a><br />";
            }
        }
    }

    public function _test()
    {
        //exit();
    }
}
