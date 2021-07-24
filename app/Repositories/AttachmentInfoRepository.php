<?php
declare(strict_types = 1);

namespace ModulePassport\Repositories;

class AttachmentInfoRepository extends AbstractRepository
{

    protected function _sceneFields()
    {
        return [
            'list' => ['id', 'attachment_id', 'app', 'info_table', 'info_field', 'info_id', 'in_use', 'file'],
            'listSearch' => ['id', 'attachment_id', 'app', 'info_table', 'info_field', 'info_id', 'in_use'],
            'add' => ['attachment_id', 'app', 'info_table', 'info_field', 'info_id'],//, 'in_use'],
        ];
    }

    public function getSearchFields()
    {
        return [
            'app' => ['type' => 'select'],
        ];
    }

    public function getShowFields()
    {
        return [
            'app' => ['valueType' => 'key'],
            'file' => ['showType' => 'image', 'valueType' => 'callback', 'method' => 'getFileInfo'],
            //'region' => ['valueType' => 'point', 'resource' => 'region'],
        ];
    }

    public function getFileInfo($model, $field)
    {
        $data = $model->attachment->toArray();
        $data['filepath'] = $model->attachment->getFullFilepath();
        return $data;
    }

    protected function _appKeyDatas()
    {
        return $this->getRepositoryObj('attachmentPath')->_systemKeyDatas();
    }

    public function getHaveSelection($scene)
    {
        return true;
    }

    public function getSelectionOperations($scene)
    {
        return [
            'select' => ['name' => '确定选中', 'operation' => 'select'],
        ];
    }

    public function _getFieldOptions()
    {
        return [
            'file' => ['width' => '180'],
        ];
    }

    public function getDatas($params)
    {
        $infos = $this->findWhere($params);
        $datas = [];
        foreach ($infos as $info) {
            $datas[] = $this->getFileInfo($info, '');
        }
        return $datas;
    }

    public function getData($params, $onlyUrl = false)
    {
        $info = $this->findWhereOne($params);
        if (empty($info)){
            return $onlyUrl ? '' : [];
        }
        $data = $this->getFileInfo($info, '');
        if ($onlyUrl) {
            return $data['filepath'] ?? '';
        }
        return $data;
    }
}
