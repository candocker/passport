<?php

declare(strict_types = 1);

namespace ModulePassport\Repositories;

class ApplicationRepository extends AbstractRepository
{
    protected function _sceneFields()
    {
        return [
            'list' => ['code', 'name', 'description', 'status'],
            'listSearch' => ['code', 'name'],
            'add' => ['code', 'name', 'description', 'status'],
            'update' => ['name', 'description', 'status'],
        ];
    }

    public function getShowFields()
    {
        return [
            //'type' => ['valueType' => 'key'],
        ];
    }

    public function getSearchFields()
    {
        return [
            //'type' => ['type' => 'select'],
        ];
    }

    public function getFormFields()
    {
        return [
            //'type' => ['type' => 'select'],
        ];
    }

    protected function _statusKeyDatas()
    {
        return [
            0 => '新建',
            1 => '使用中',
            99 => '锁定',
        ];
    }
}
