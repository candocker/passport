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
	/*public function _statusKeyDatas()
	{
		return [
			'nav' => '导航标签',
			'hot' => '热门标签',
			'comment' => '推荐标签',
		];
	}

	public function _infoTypeKeyDatas()
	{
		return [
			'book' => '书籍',
			'chapter' => '段落内容',
			'author' => '作者',
		];
    }*/
}
