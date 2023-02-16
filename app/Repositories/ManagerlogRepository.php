<?php
declare(strict_types = 1);

namespace ModulePassport\Repositories;

class ManagerlogRepository extends AbstractRepository
{

    protected function _statusKeyDatas()
    {
        return [
            0 => '未激活',
            1 => '使用中',
            99 => '锁定',
        ];
    }

    protected function _sceneFields()
    {
        return [
            'list' => ['id', 'manager_id', 'manager_name', 'role', 'menu_code', 'menu_name', 'created_at', 'data', 'data_pre'],
            'listSearch' => ['id', 'manager_id', 'manager_name', 'role', 'menu_code', 'menu_name', 'created_at'],
        ];
    }

    public function getShowFields()
    {
        return [
            //'data' => ['showType' => 'hidden'],
        ];
    }

    public function getSearchFields()
    {
        return [
            'user_id' => ['type' => 'input'],
            'last_at' => ['type' => 'datetimerange'],
        ];
    }

    public function getSearchDealFields()
    {
        return [
            'user_id' => ['type' => 'relate', 'elem' => 'user', 'operator' => 'like', 'field' => 'name'],
        ];
    }

    public function _getFieldOptions()
    {
        return [
            'data' => ['hidden' => 1],
            'data_pre' => ['hidden' => 1],
        ];
    }
}
