<?php
declare(strict_types = 1);

namespace ModulePassport\Repositories;

class PermissionRepository extends AbstractRepository
{
    public function cacheRouteDatas()
    {
        $datas = $this->_getDatas();
        $this->setPointCaches('permission', $datas);
        return $datas;
    }

    protected function _getRoutePath($data, $action)
    {
        if (!empty($data['route'])) {
            return $data['route'];
        }
        $code = str_replace('_', ' ', $data['resource_code']);
        $code = $this->resource->strOperation($code, 'pluralStudly');//Str::pluralStudly($code);
        $path = '/' . str_replace(' ', '-', $code);
        if (in_array($action, ['listinfo', 'add'])) {
            return $path;
        }
        if (in_array($action, ['put', 'update', 'edit', 'delete', 'view'])) {
            return $path . "/{id:\d+}";
        }
        return $path . "/{$data['action']}";
    }

    protected function _sceneFields()
    {
        return [
            'list' => ['name', 'app', 'code', 'parent_code', 'resource_code', 'orderlist', 'controller', 'route', 'route_path', 'action', 'method', 'display', 'updated_at', 'extparam'],
            //'base' => ['name', 'app', 'code', 'controller', 'route', 'action', 'method'],
            'view' => ['name', 'app', 'code', 'controller', 'route', 'action', 'method'],
            'listSearch' => ['code', 'app', 'resource_code', 'parent_code', 'name', 'updated_at'],
            'add' => ['app', 'resource_code', 'parent_code', 'code', 'name', 'controller', 'action', 'method', 'route', 'route_path', 'orderlist', 'display', 'icon'],
            'update' => ['app', 'resource_code', 'parent_code', 'code', 'name', 'controller', 'action', 'method', 'route', 'route_path', 'orderlist', 'display', 'icon'],
        ];
    }

    public function getShowFields()
    {
        return [
            'orderlist' => ['showType' => 'edit'],
        ];
    }

    public function _getFieldOptions()
    {
        return [
            'name' => ['width' => '200', 'align' => 'left'],
            'orderlist' => ['width' => '100'],
        ];
    }

    public function getFormFields()
    {
        return [
            'resource_code' => ['type' => 'select', 'infos' => $this->getPointKeyValues('resource')],
            'app' => ['type' => 'select'],
            'method' => ['type' => 'select'],
            'display' => ['type' => 'select'],
            'parent_code' => ['type' => 'cascader', 'props' => ['value' => 'code', 'label' => 'name', 'children' => 'subInfos', 'checkStrictly' => true], 'infos' => $this->getPointTreeDatas(null, 2, 'list')],
        ];
    }

    protected function _methodKeyDatas()
    {
        return [
            'get' => 'GET',
            'post' => 'POST',
            'put' => 'PUT',
            'delete' => 'DELETE',
        ];
    }

    protected function _displayKeyDatas()
    {
        return [
            1 => '一级分类',
            2 => '二级分类',
            3 => '左侧操作',
            4 => '顶部操作',
            5 => '记录操作',
            90 => '指定路由',
            99 => '指定位置操作',
        ];
    }

    protected function _appKeyDatas()
    {
        return $this->config->get('local_params.apps');
    }

    public function getSearchFields()
    {
        return [
            'app' => ['type' => 'select', 'multiple' => 1],
            'parent_code' => ['type' => 'cascader', 'props' => ['value' => 'code', 'label' => 'name', 'children' => 'subInfos', 'checkStrictly' => true, 'multiple' => true], 'infos' => $this->getPointTreeDatas(null, 2, 'list')],
            'resource_code' => ['type' => 'selectSearch', 'searchResource' => 'resource', 'multiple' => 1],
        ];
    }

    public function getSearchDealFields()
    {
        return [
            'app' => ['type' => 'multiple'],
            'resource_code' => ['type' => 'multiple'],
        ];
    }

    protected function _getDatas()
    {
        $infos = $this->model->all();
        $datas = [];
        foreach ($infos as $info) {
            $action = $info['action'];
            if (empty($action)) {
                continue ;
            }
            $callback = in_array($action, ['listinfo', 'add', 'update', 'delete', 'view']) ? $action . 'General' : $action;
            $method = (array) explode(',', $info['method']);
            $method = array_filter(array_unique($method));
            if (empty($method)) {
                $method = ['get'];
            }
            foreach ($method as & $value) {
                $value = strtoupper($value);
            }

            $app = $info['app'];
            $controller = $this->resource->formatClass('controller', $info['resource_code'], $app);
            $path = $this->_getRoutePath($info, $action);
            $path = $this->_formatPath($path);
            $datas[$app][$info['controller']][$action] = [
                'code' => $info['code'],
                'method' => $method,
                'path' => $path,
                'callback' => $controller . '@' . $this->resource->strOperation($callback, 'camel'),
            ];
        }
    
        return $datas;
    }

    protected function _formatPath($path)
    {
        $path = str_replace('}]', '?}', $path);
        $path = str_replace([':\d+', '[', ']'], ['', '', ''], $path);
        return $path;
    }
}
