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

    public function _testCreateBase()
    {
        $elems = $this->getModelObj('passport-resource')->where(['app' => 'inksystem'])->get();
        $oPath = '/data/app/system-produce/library/inksystem/app/';
        $nPath = '/data/app/system-produce/library/prosystem/app/CommonTrait/';
        $command = '';
        foreach ($elems as $resource) {
            $elem = $resource['code'];
            $class = $this->resource->strOperation($elem, 'studly');
            $types = ['Controller' => 'Controllers', 'Model' => 'Models', 'Repository' => 'Repositories', 'Service' => 'Services'];
            foreach ($types as $bType => $type) {
                $oFile = $bType == 'Model' ? $oPath . "{$type}/{$class}.php" : $oPath . "{$type}/{$class}{$bType}.php";
                if (!file_exists($oFile)) {
                    continue;
                }
                $fullClass = $bType == 'Model' ? $class : "{$class}{$bType}";
                $str = "<?php\n\ndeclare(strict_types = 1);\n\nnamespace ModuleInksystem\\{$type};\n\nuse ModuleProsystem\CommonTrait\\{$type}\\{$class}Trait;\n\nclass {$fullClass} extends Abstract{$bType}\n{\n    use {$class}Trait;\n}";
                file_put_contents($oFile, $str);
                echo $str;
            }
            /*foreach ($types as $bType => $type) {
                $oFile = $bType == 'Model' ? $oPath . "{$type}/{$class}.php" : $oPath . "{$type}/{$class}{$bType}.php";
                $nFile = $bType == 'Model' ? $nPath . "{$type}/{$class}Trait.php" : $nPath . "{$type}/{$class}Trait.php";
                if (file_exists($oFile)) {
                    $content = file_get_contents($oFile);
                    $oParams = ['ModuleBrushpen\\' . $type, 'class ', $class];
                    if ($bType == 'Model') {
                        $oParams[] = " extends Abstract{$bType}";
                    } else {
                        $oParams[] = "{$bType} extends Abstract{$bType}";
                    }
                    print_r($oParams);
                    $content = str_replace($oParams, ["ModuleProsystem\CommonTrait\\{$type}", 'trait ', $class . 'Trait', ''], $content);
                    file_put_contents($nFile, $content);
                } else {
                    var_dump($oFile);
                }
            }*/
        }
        exit();
    }

    public function _testChangeApp()
    {
        return false;
        $appNew = 'prosystem';
        $appOld = 'printsys';
        $elems = [
            'goods','goods-inventory','goods-inventory-record','material-inventory','material-inventory-day',
            'material-inventory-record','material-sku','material-subsidiary','member','merchant','orderraw-inventory',
            'orderraw-inventory-detail','orderraw-purchase','orderraw-purchase-detail','orderraw-purchase-record',
            'orderraw-putinout','orderraw-putinout-detail','orderraw-putinout-record','statistic-member','statistic-orderjob-day',
            'statistic-produce','supplier','supplier-bill','team-member'
        ];
        $elems = ['team-member'];
        $elems = ['statistic-member', 'statistic-produce', 'statistic-orderjob-day'];
        $elems = ['material-main', 'material-specs', 'material-custom', 'brand'];

        //$oPath = '/data/app/system-admin/src/applications/printsys/';
        //$nPath = '/data/app/system-admin/src/applications/prosystem/';
        $oPath = '/data/app/system-produce/library/printsys/app/';
        $nPath = '/data/app/system-produce/library/prosystem/app/';
        $command = '';
        foreach ($elems as $elem) {
            $resource = $this->getModelObj('resource')->where(['app' => 'printsys', 'code' => $elem])->first();
            $class = $this->resource->strOperation($elem, 'studly');
            $files = [
                "Controllers/{$class}Controller.php",
                "Models/{$class}.php",
                "Observers/{$class}Observer.php",
                "Requests/{$class}Request.php",
                "Resources/{$class}.php",
                "Resources/{$class}Collection.php",
                "Repositories/{$class}Repository.php",
                "Services/{$class}Service.php",
            ];

            /*foreach ($files as $file) {
                $oFull = $oPath . $file;
                if (!file_exists($oFull)) {
                    var_dump($oFull);
                    continue;
                }
                $content = file_get_contents($oFull);
                $content = str_replace('Printsys', 'Prosystem', $content);
                $content = str_replace('printsys', 'prosystem', $content);
                $nFull = $nPath . $file;
                file_put_contents($nFull, $content);
                $command .= "rm -f {$oFull};\n";
            }*/


            /*$oFile = $oPath . $class . '.js';
            if (!file_exists($oFile)) {
                var_dump($class);
                continue;
            }*/
            //$command .= "rm -f {$oFile};\n";
            /*$content = file_get_contents($oFile);
            $content = str_replace('printsys', 'prosystem', $content);
            $nFile = $nPath . $fileName . '.js';
            file_put_contents($nFile, $content);*/

            /*$resource->app = $appNew;
            $r = $resource->save();
            var_dump($r);*/
        }
        //echo $command;
        //exit();

        $permissions = $this->getModelObj('permission')->whereIn('resource_code', $elems)->get();
        foreach ($permissions as $permission) {
            $permission->code = str_replace($appOld, $appNew, $permission->code);
            $parentCode = $permission->parent_code;
            /*if (strpos($parentCode, $appOld) !== false) {
                var_dump($parentCode);
                $parentNew = str_replace($appOld, $appNew, $parentCode);
                $pInfo = $this->getModelObj('permission')->where(['code' => $parentCode])->first();
                if (!empty($pInfo)) {
                    $pInfo->code = $parentNew;
                    $pInfo->save();
                }

                $permission->parent_code = $parentNew;
            }*/
            $permission->app = $appNew;
            //print_r($permission->toArray());
            $permission->save();
        }
    }

    protected function _testEditResource()
    {
        $app = 'printsys';
        $rCode = 'orderjob-backend';
        $resource = $this->getModelObj('resource')->where(['app' => $app, 'code' => $rCode])->first();

        $class = ucfirst(Str::camel($rCode));
        $pre = in_array($app, ['printsys', 'wmsystem']) ? 'library' : 'vendor/candocker';
        $command = "\nmore {$pre}/{$app}/app/Controllers/{$class}Controller.php\n";
        $command .= "more {$pre}/{$app}/app/Models/{$class}.php\n";
        $command .= "more {$pre}/{$app}/app/Requests/{$class}Request.php\n";
        $command .= "more {$pre}/{$app}/app/Resources/{$class}.php\n";
        $command .= "more {$pre}/{$app}/app/Resources/{$class}Collection.php\n";
        $command .= "more {$pre}/{$app}/app/Repositories/{$class}Repository.php\n\n";

        $resource['controller'] || $command .= "rm -f {$pre}/{$app}/app/Controllers/{$class}Controller.php\n";
        $resource['model'] || $command .= "rm -f {$pre}/{$app}/app/Models/{$class}.php\n";
        $resource['repository'] || $command .= "rm -f {$pre}/{$app}/app/Repositories/{$class}Repository.php\n";
        $resource['request'] || $command .= "rm -f {$pre}/{$app}/app/Requests/{$class}Request.php\n";
        $resource['resource'] || $command .= "rm -f {$pre}/{$app}/app/Resources/{$class}.php\n";
        $resource['collection'] || $command .= "rm -f {$pre}/{$app}/app/Resources/{$class}Collection.php\n";
        echo $command;

        if ($resource['controller']) {
            return true;
        }
        $pCodeStr = '';
        $pInfos = $this->getModelObj('permission')->where(['app' => $app, 'resource_code' => $rCode])->get();
        foreach ($pInfos as $pInfo) {
            $pCodeStr .= "{$pInfo['code']}','";
        }
        $pCodeStr = rtrim($pCodeStr, "','");

        $sql = "\n\nDELETE FROM `wp_auth_role_permission` WHERE `permission_code` IN ('{$pCodeStr}');\n";
        $sql .= "DELETE FROM `wp_auth_permission` WHERE `code` IN ('{$pCodeStr}');\n";

        echo $sql;
        //SELECT * FROM `wp_auth_permission` WHERE `parent_code` IN ('printsys_print', 'printsys_backend', 'printsys_product', 'printsys_bench', 'printsys_productpurchase') ORDER BY `wp_auth_permission`.`controller` ASC
    }

    protected function _testRenameResource()
    {
        $app = 'printsys';
        $rCode = 'material-inputout-record';
        $newCode = 'material-inventory-record';
        $table = str_replace('-', '_', $rCode);
        $newTable = str_replace('-', '_', $newCode);

        $resource = $this->getModelObj('resource')->where(['app' => $app, 'code' => $rCode])->first();
        $pCodeStr = '';
        $pInfos = $this->getModelObj('permission')->where(['app' => $app, 'resource_code' => $rCode])->get();
        foreach ($pInfos as $pInfo) {
            $pCodeStr .= "{$pInfo['code']}','";
        }
        $pCodeStr = rtrim($pCodeStr, "','");
        $sql = "RENAME TABLE `dev_printsys`.`ps_{$table}` TO `dev_printsys`.`ps_{$newTable}`;\n\n";

        $sql .= "UPDATE `wp_auth_resource` SET `code` = '{$newCode}' WHERE `app` = '{$app}' AND `code` = '{$rCode}';\n";
        $sql .= "UPDATE `wp_auth_permission` SET `resource_code` = '{$newCode}', `controller` = '{$newCode}' WHERE `code` IN ('{$pCodeStr}');\n";
        $sql .= "UPDATE `wp_auth_permission` SET `code` = REPLACE(`code`, '{$rCode}', '{$newCode}') WHERE `code` IN ('{$pCodeStr}');\n";
        $sql .= "UPDATE `wp_auth_role_permission` SET `permission_code` = REPLACE(`permission_code`, '{$rCode}', '{$newCode}') WHERE `permission_code` IN ('{$pCodeStr}');\n";
        echo $sql;

        $class = ucfirst(Str::camel($rCode));
        $newClass = ucfirst(Str::camel($newCode));
        $pre = in_array($app, ['printsys', 'wmsystem']) ? 'library' : 'vendor/candocker';
        $pathPre = base_path() . "/{$pre}/{$app}/app/";
        $elems = [
            $pathPre . "Controllers/{$class}Controller.php",
            $pathPre . "Models/{$class}.php",
            $pathPre . "Requests/{$class}Request.php",
            $pathPre . "Resources/{$class}.php",
            $pathPre . "Resources/{$class}Collection.php",
            $pathPre . "Repositories/{$class}Repository.php",
        ];
        $command = " \n";
        foreach ($elems as $file) {
            $newFile = str_replace($class, $newClass, $file);

            $content = file_get_contents($file);
            $content = str_replace($class, $newClass, $content);
            $content = str_replace($table, $newTable, $content);
            file_put_contents($file, $content);
            $command .= "mv {$file} {$newFile}\n";
        }

        echo $command;

    }

    protected function _testDeleteResource()
    {
        $app = 'printsys';
        $rCode = 'team-performance';

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
        $command = "\nmore {$pre}/{$app}/app/Controllers/{$class}Controller.php\n";
        $command .= "more {$pre}/{$app}/app/Models/{$class}.php\n";
        $command .= "more {$pre}/{$app}/app/Requests/{$class}Request.php\n";
        $command .= "more {$pre}/{$app}/app/Resources/{$class}.php\n";
        $command .= "more {$pre}/{$app}/app/Resources/{$class}Collection.php\n";
        $command .= "more {$pre}/{$app}/app/Repositories/{$class}Repository.php\n\n";
        echo $command;

        $command = "rm -f {$pre}/{$app}/app/Controllers/{$class}Controller.php\n";
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
        $config = $this->config->get('local_params.resourcePath');
        $dataConfig = config('database');
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
        $this->getRepositoryObj('resource')->cacheResourceDatas();
        $repository->cacheRouteDatas();
        //$repository->setPointCaches('region');
        $resources = $repository->getPointCaches('resource');
        //print_r($resources);exit();
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

    public function _testDealTable()
    {
        $tables = ['', 'Backend', 'Print', 'Client', 'Product', 'VirtualProduct', 'Part'];
        $sql = '';
        foreach ($tables as $table) {
            $modelKey = "orderjob{$table}";
            $model = $this->getModelObj($modelKey);
            $tableName = $model->getConnection()->getTablePrefix() . $model->getTable();
            $results = $model->getConnection()->getDoctrineSchemaManager()->listTableColumns($tableName);
            foreach ($results as $field => $result) {
                $type = $result->getType()->getName();
                $comment = $result->getComment();
                if ($type == 'smallint') {
                    $sql .= "ALTER TABLE `{$tableName}` CHANGE `{$field}` `{$field}` INT NOT NULL DEFAULT '0' COMMENT '{$comment}';\n";
                }
                //var_dump($field, $type);
            }
        }
        echo $sql;
        //print_r($results);
        exit();
    }
}
