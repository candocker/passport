<?php
declare(strict_types = 1);

namespace ModulePassport\Repositories;

class RegionRepository extends AbstractRepository
{

    protected function _statusKeyDatas()
    {
        return [
            0 => '未激活',
            1 => '使用中',
            99 => '锁定',
        ];
    }

	protected function _levelKeyDatas()
	{
		return [
			'province' => '省级',
			'city' => '市级',
			'county' => '区县',
		];
	}

    protected function _sceneFields()
    {
        return [
            'list' => ['code', 'name', 'parent_code'],
            'listSearch' => ['code', 'name', 'parent_code'],
            'add' => ['code', 'name', 'parent_code', 'name', 'name_full', 'spell_one', 'spell', 'spell_short', 'level', 'orderlist', 'status'],
            //'update' => ['name', 'parent_code', 'name', 'name_full', 'spell_one', 'spell', 'spell_short', 'level', 'orderlist', 'status'],
            'update' => ['name', 'parent_code', 'name', 'name_full'],
        ];
    }

    public function getFormFields()
    {
        return [
            'parent_code' => ['type' => 'cascader', 'props' => ['value' => 'code', 'label' => 'name', 'children' => 'subInfos', 'checkStrictly' => true], 'infos' => $this->getPointTreeDatas('region', 2, 'list')],
        ];
    }

    public function getSearchFields()
    {
        return [
            'app' => ['type' => 'select', 'multiple' => 1],
            'parent_code' => ['type' => 'cascader', 'props' => ['value' => 'code', 'label' => 'name', 'children' => 'subInfos', 'checkStrictly' => true, 'multiple' => true], 'infos' => $this->getPointTreeDatas(null, 2, 'list')],
        ];
    }
}
