<?php
declare(strict_types = 1);

namespace ModulePassport\Repositories;

class ResourceRepository extends AbstractRepository
{
    public function cacheResourceDatas()
    {
        $datas = $this->_getDatas();
        $this->setPointCaches('resource', $datas);
        return $datas;
    }

    public function getFormFields()
    {
        $return = [
            'app' => ['type' => 'select', 'infos' => $this->getKeyValues('app')],
        ];
        foreach ($this->_getElems() as $elem) {
            $return[$elem] = ['type' => 'selectInput', 'infos' => $this->getKeyValues('resource'), 'customValue' => 3];
        }
        return $return;
    }

    protected function _appKeyDatas()
    {
        return $this->config->get('local_params.apps');
    }

    protected function _resourceKeyDatas()
    {
        return [
            '' => '无',
            '1' => '默认',
            '3' => '自定义',
        ];
    }

    public function getSearchFields()
    {
        return [
            'app' => ['type' => 'select', 'infos' => $this->getKeyValues('app')],
        ];
    }

    public function getShowFields()
    {
        $return = [
            'app' => ['valueType' => 'key'],
        ];
        foreach ($this->_getElems() as $elem) {
            $return[$elem] = ['valueType' => 'callback', 'method' => 'formatValue'];
        }
        return $return;
    }

    public function formatValue($model, $field)
    {
        $value = $model->$field;
        if (in_array($value, array_keys($this->_resourceKeyDatas()))) {
            return $this->_resourceKeyDatas()[$value];
        }
        return $value;
    }

    protected function _sceneFields()
    {
        return [
            'list' => ['app', 'code', 'name', 'controller', 'request', 'service', 'repository', 'model', 'resource', 'collection', 'updated_at'],
            'listSearch' => ['code', 'app', 'name', 'updated_at'],
            'add' => ['app', 'code', 'name', 'controller', 'request', 'service', 'repository', 'model', 'resource', 'collection'],
            'update' => ['app', 'code', 'name', 'controller', 'request', 'service', 'repository', 'model', 'resource', 'collection'],
        ];
    }

    public function cacheDatass()
    {
        $redis = $this->container->get(\Psr\SimpleCache\CacheInterface::class);
        $redis->set('resource-infos', serialize(['a' => 'b']));

        $r = $redis->keys('*');
        $a = $redis->get('resource-infos');
        var_dump(unserialize($a));
        print_r($r);
    }

    protected function _getElems()
    {
        return ['controller', 'request', 'service', 'repository', 'model', 'resource', 'collection'];
    }

    protected function _getDatas()
    {
        $infos = $this->model->all();
        $datas = [];
        foreach ($infos as $info) {
            $code = $info['code'];
            $codeCamel = $this->resource->strOperation($code, 'camel');
            $app = $info['app'];
            foreach (['controller', 'model', 'request', 'service', 'repository', 'resource', 'collection'] as $elem) {
                if (empty($info[$elem])) {
                    continue;
                }
                $datas[$app. '-' . $codeCamel][$elem] = $info[$elem] != 1 ? $info[$elem] : $this->resource->formatClass($elem, $code, $app);
            }
        }
        return $datas;
    }
}
