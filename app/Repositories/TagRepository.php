<?php

declare(strict_types = 1);

namespace ModulePassport\Repositories;

class TagRepository extends AbstractRepository
{
    protected function _sceneFields()
    {
        return [
            'list' => ['code', 'sort', 'name', 'description', 'created_at', 'status'],
            'listSearch' => ['name', 'code', 'status', 'created_at'],
            'add' => ['code', 'sort', 'name', 'description', 'status'],
            'update' => ['code', 'sort', 'name', 'description', 'status'],
        ];
    }

    public function getShowFields()
    {
        return [
            'sort' => ['valueType' => 'key'],
        ];
    }

    public function getSearchFields()
    {
        return [
            'sort' => ['type' => 'select'],
        ];
    }

    public function getFormFields()
    {
        return [
            'sort' => ['type' => 'select'],
        ];
    }

    protected function _statusKeyDatas()
    {
        return [
            0 => '未激活',
            1 => '使用中',
            99 => '锁定',
        ];
    }

    protected function _sortKeyDatas()
    {
        return [
            '' => '未分类',
            'topic' => '话题',
        ];
    }
}
