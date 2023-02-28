<?php

declare(strict_types = 1);

namespace ModulePassport\Repositories;

class TagInfoRepository extends AbstractRepository
{
    protected function _sceneFields()
    {
        return [
            'list' => ['id', 'tag_code', 'app', 'info_table', 'info_id', 'orderlist'],
            'listSearch' => ['id', 'app', 'info_table', 'info_id', 'info_name'],
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
            //'type' => ['type' => 'select', 'infos' => $this->getKeyValues('type')],
        ];
    }

    public function getFormFields()
    {
        return [
            //'type' => ['type' => 'select', 'infos' => $this->getKeyValues('type')],
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

    public function getDatas($params)
    {
        $infos = $this->findWhere($params);
        $datas = [];
        foreach ($infos as $info) {
            $data = $info->toArray();
            $data['name'] = $info->tag->name;
            $datas[] = $data;
        }
        return $datas;
    }
}
