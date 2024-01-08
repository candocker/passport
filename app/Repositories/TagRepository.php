<?php

declare(strict_types = 1);

namespace ModulePassport\Repositories;

class TagRepository extends AbstractRepository
{
    protected function _sceneFields()
    {
        return [
            'list' => ['code', 'sort', 'name', 'brief', 'created_at'],
            'listSearch' => ['name', 'code', 'created_at'],
            'keyvalueExtSearch' => ['keyword'],
            'add' => ['code', 'sort', 'name', 'brief'],
            'update' => ['code', 'sort', 'name', 'brief'],
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

    protected function _sortKeyDatas()
    {
        return [
            '' => '未分类',
            'comment' => '推荐',
            'hot' => '热门',
            'nav' => '导航',
            'topic' => '话题',
        ];
    }
}
