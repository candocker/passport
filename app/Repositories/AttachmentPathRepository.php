<?php
declare(strict_types = 1);

namespace ModulePassport\Repositories;

class AttachmentPathRepository extends AbstractRepository
{
    protected function _sceneFields()
    {
        return [
            'list' => ['id', 'path', 'parent_id', 'system', 'path_full', 'name', 'type', 'created_at'],
            'listSearch' => ['id', 'parent_id', 'system', 'business', 'path_full', 'name', 'type', 'created_at'],
            'keyvalueExtSearch' => ['id', 'parent_id', 'path', 'path_full', 'name', 'type', 'created_at'],
            'add' => ['system', 'business', 'parent_id', 'path', 'name', 'tag', 'type', 'status'],
            'update' => ['business', 'name', 'tag', 'type', 'status'],
        ];
    }

    public function getSearchFields()
    {
        $infos = $this->getPointKeyValues('attachmentPath', ['parent_id' => 0], 'keyvalueExt');
        array_unshift($infos['data'], ['id' => 0, 'name' => '根目录', 'extField' => '', 'extField2' => '']);
        return [
            'system' => ['type' => 'select'],
            'business' => ['type' => 'select'],
            'parent_id' => ['type' => 'cascaderLoad', 'props' => ['value' => 'code', 'label' => 'name', 'children' => 'subInfos', 'checkStrictly' => true], 'loadApp' => 'passport', 'loadResource' => 'attachment-path', 'infos' => $infos],
        ];
    }

    public function getFormFields()
    {
        $infos = $this->getPointKeyValues(null, ['parent_id' => 0], 'keyvalueExt');
        array_unshift($infos['data'], ['id' => 0, 'name' => '根目录', 'extField' => '', 'extField2' => '']);
        return [
            'system' => ['type' => 'select'],
            'type' => ['type' => 'select'],
            'business' => ['type' => 'select'],
            'parent_id' => ['type' => 'cascaderLoad', 'props' => ['value' => 'code', 'label' => 'name', 'children' => 'subInfos', 'checkStrictly' => true], 'loadApp' => 'passport', 'loadResource' => 'attachment-path', 'infos' => $infos],
        ];
    }

    public function _typeKeyDatas()
    {
        return [
            'public' => '公开',
            'protected' => '保护',
        ];
    }

    public function _statusKeyDatas()
    {
        return [
            '0' => '通用',
            '1' => '专用',
            '99' => '备用',
        ];
    }

    public function _systemKeyDatas()
    {
        $datas = [];
        foreach ($this->systemDatas() as $key => $value) {
            $datas[$key] = $value['name'];
        }
        return $datas;
    }

    public function systemDatas()
    {
        return $this->config->get('local_params.attachment.system');
    }

    public function getShowFields()
    {
        return [
            'system' => ['valueType' => 'key'],
            'status' => ['valueType' => 'key'],
            'type' => ['valueType' => 'key'],
            'business' => ['valueType' => 'key'],
        ];
    }

    public function _getFieldOptions()
    {
        return [
            'path' => ['width' => '200'],
            'id' => ['width' => '60'],
            'path_full' => ['width' => '300'],
        ];
    }

    protected function _businessKeyDatas()
    {
        return $this->config->get('local_params.attachment.business');
    }
}
