<?php

declare(strict_types = 1);

namespace ModulePassport\Controllers;

use Illuminate\Support\Str;

class TestController extends AbstractController
{
    public function test()
    {
        //$permissions = $this->resource->getBaseCache('permission');
        //print_r($permissions);exit();
        $request = $this->request;
        $inTest = config('app.inTest');
        if (empty($inTest)) {
            return $this->error(400, '非法请求');
        }
        $method = ucfirst($request->input('method', ''));
        $method = "_test{$method}";
        $this->$method($request);
    }

    protected function _testChangeapp()
    {
        $appNew = 'knowledge';
        //$appOld = 'culture';
        $appOld = 'infocms';
        $elems = [
            'navsort' => 'navsort',
            //'knowledge' => 'knowledge',
            //'knowledge_detail' => 'knowledge_detail',
            //'resource_info' => 'resource_info',
            //'resource_detail' => 'resource_detail',
            /*'subject' => 'subject',
            'subject_sort' => 'subject_sort',
            'group_subject' => 'group_subject',
            'group' => 'group',*/

            /*'figure_title' => 'figure_title',
            'dateinfo' => 'dateinfo',
            'book_figure' => 'book_figure',*/
            //'dynasty' => 'dynasty',

            //'book_publish' => 'book_listing',
            /*'book' => 'book',
            'figure' => 'figure',
            'series' => 'book_catalog',
            'series_volume' => 'book_volume',
            'chapter' => 'chapter',*/
        ];

        $oPath = '/data/htmlwww/laravel-system/vendor/candocker/' . $appOld . '/app/';
        $nPath = '/data/htmlwww/laravel-system/vendor/candocker/' . $appNew . '/app/';
        $command = '';
        foreach ($elems as $oldResource => $newResource) {
            $oldClass = $this->resource->strOperation($oldResource, 'studly');
            $newClass = $this->resource->strOperation($newResource, 'studly');

            $files = [
                "Controllers/{{CLASS}}Controller.php",
                "Models/{{CLASS}}.php",
                "Observers/{{CLASS}}Observer.php",
                "Requests/{{CLASS}}Request.php",
                "Resources/{{CLASS}}.php",
                "Resources/{{CLASS}}Collection.php",
                "Repositories/{{CLASS}}Repository.php",
                "Services/{{CLASS}}Service.php",
            ];

            foreach ($files as $file) {
                $oldFile = $oPath . str_replace('{{CLASS}}', $oldClass, $file);
                if (!file_exists($oldFile)) {
                    //var_dump($oldFile);
                    continue;
                }
                $content = file_get_contents($oldFile);
                $content = str_replace(ucfirst($appOld), ucfirst($appNew), $content);
                $content = str_replace($oldClass, $newClass, $content);
                $content = str_replace($appOld, $appNew, $content);
                $content = str_replace($oldResource, $newResource, $content);
                $newFile = $nPath . str_replace('{{CLASS}}', $newClass, $file);
                var_dump($newFile);
                file_put_contents($newFile, $content);
                $command .= "rm -f {$oldFile};\n";
            }
        }
        echo $command;
        //exit();
    }

    protected function _testDeleteResource()
    {
        $app = 'infocms';
        //$rCode = 'order-inventory-detail';
        //$rCode = 'order-putin-shop';
        $rCode = 'brand';

        $resource = $this->getModelObj('resource')->where(['app' => $app, 'code' => $rCode])->first();
        $pCodeStr = '';
        $pInfos = $this->getModelObj('permission')->where(['app' => $app, 'resource_code' => $rCode])->get();
        foreach ($pInfos as $pInfo) {
            $pCodeStr .= "{$pInfo['code']}','";
        }
        $pCodeStr = rtrim($pCodeStr, "','");

        $sql = "DELETE FROM `wp_auth_resource` WHERE `app` = '{$app}' AND `code` = '{$rCode}';\n";
        $sql .= "DELETE FROM `wp_auth_role_permission` WHERE `permission_code` IN ('{$pCodeStr}');\n";
        $sql .= "DELETE FROM `wp_auth_permission` WHERE `code` IN ('{$pCodeStr}');\n";

        $class = ucfirst(Str::camel($rCode));
        $pre = in_array($app, ['printsys', 'wmsystem']) ? 'library' : 'vendor/candocker';
        echo $sql;
        $command = "more {$pre}/{$app}/app/Controllers/{$class}Controller.php\n";
        $command .= "more {$pre}/{$app}/app/Models/{$class}.php\n";
        $command .= "more {$pre}/{$app}/app/Resources/{$class}.php\n";
        $command .= "more {$pre}/{$app}/app/Resources/{$class}Collection.php\n";
        $command .= "more {$pre}/{$app}/app/Requests/{$class}Request.php\n";
        $command .= "more {$pre}/{$app}/app/Repositories/{$class}Repository.php\n\n";

        $command .= "rm -f {$pre}/{$app}/app/Controllers/{$class}Controller.php\n";
        $command .= "rm -f {$pre}/{$app}/app/Models/{$class}.php\n";
        $command .= "rm -f {$pre}/{$app}/app/Repositories/{$class}Repository.php\n";
        $command .= "rm -f {$pre}/{$app}/app/Requests/{$class}Request.php\n";
        $command .= "rm -f {$pre}/{$app}/app/Resources/{$class}.php\n";
        $command .= "rm -f {$pre}/{$app}/app/Resources/{$class}Collection.php\n";

        echo $command;

    }

    protected function _testUpdateResource()
    {
        $service = $this->getServiceObj('userPermission');
        $service->updatePermission();
        //$service->updateResource();
    }

    public function _testCheckResource($request)
    {
        $config = $this->config->get('local_params');
        $dataConfig = config('database');
        $this->getRepositoryObj('resource')->cacheResourceDatas();
        $this->getRepositoryObj('permission')->cacheRouteDatas();
        print_r($dataConfig);
        $command = new \Framework\Baseapp\Commands\GenResourceCommand();
        $command->checkResource($dataConfig['connections'], $config);
        print_r($config);exit();
    }

    public function _testRepositoryStr()
    {
        $command = new \Framework\Baseapp\Commands\GenResourceCommand();
        $connection = $this->request->input('connection', '');
        $table = $this->request->input('table', '');
        $str = $command->getPointField($connection, $table, 'string');
        echo "            'list' => [{$str}],";exit();
    }

    public function _testResource($request)
    {
        //\DB::update("TRUNCATE `wp_auth_role_permission`;");
        //\DB::update("REPLACE INTO `wp_auth_role_permission`(`role_code`, `permission_code`, `created_at`) SELECT 'superman', `code`, `created_at` FROM `wp_auth_permission` WHERE 1 ;");
        $this->getRepositoryObj('resource')->cacheResourceDatas();
        $this->getRepositoryObj('permission')->cacheRouteDatas();
        //$params = $request->all();
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
        $repository = $this->getRepositoryObj('permission');
        //$repository->setPointCaches('region');
        $datas = $repository->getPointCaches('permission');
        print_R($datas);exit();
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
