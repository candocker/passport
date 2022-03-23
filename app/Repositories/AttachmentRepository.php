<?php
declare(strict_types = 1);

namespace ModulePassport\Repositories;

class AttachmentRepository extends AbstractRepository
{

    protected function _sceneFields()
    {
        return [
            'pop' => ['id', 'path_id', 'name', 'tag', 'filepath', 'extension', 'created_at'],
            'popSearch' => ['id', 'path_id', 'name', 'extension', 'created_at'],
            'list' => ['id', 'path_id', 'name', 'tag', 'filepath', 'mime_type', 'size', 'filename', 'extension', 'created_at', 'point_operation'],
            'view' => ['id', 'path_id', 'name', 'tag', 'filepath', 'mime_type', 'size', 'filename', 'extension', 'created_at'],
            'listSearch' => ['id', 'path_id', 'name', 'tag', 'filepath', 'mime_type', 'size', 'filename', 'extension', 'created_at'],
            'add' => ['system', 'path_id', 'files'],
            'create' => ['system', 'path_id', 'name', 'tag', 'filepath', 'mime_type', 'size', 'filename', 'extension'],
            'update' => ['name', 'tag'],
        ];
    }

    public function getSearchFields()
    {
        $infos = $this->getPointKeyValues('attachmentPath', ['parent_id' => 0], 'keyvalueExt');
        array_unshift($infos['data'], ['id' => 0, 'name' => '根目录', 'extField' => '', 'extField2' => '']);
        return [
            'system' => ['type' => 'select'],
            'path_id' => ['type' => 'cascaderLoad', 'props' => ['value' => 'code', 'label' => 'name', 'children' => 'subInfos', 'checkStrictly' => true], 'loadApp' => 'passport', 'loadResource' => 'attachment-path', 'infos' => $infos, 'changeMethod' => 'updateSystem'],
        ];
    }

    public function getFormFields()
    {
        $infos = $this->getPointKeyValues('attachmentPath', ['parent_id' => 0], 'keyvalueExt');
        array_unshift($infos['data'], ['id' => 0, 'name' => '根目录', 'extField' => '', 'extField2' => '']);
        return [
            'system' => ['type' => 'select'],
            'path_id' => ['type' => 'cascaderLoad', 'props' => ['value' => 'code', 'label' => 'name', 'children' => 'subInfos', 'checkStrictly' => true], 'loadApp' => 'passport', 'loadResource' => 'attachment-path', 'infos' => $infos, 'changeMethod' => 'updateSystem'],
            'files' => ['type' => 'upload'],
        ];
    }

    public function getShowFields()
    {
        return [
            'status' => ['showType' => 'common', 'valueType' => 'key'],
            'system' => ['valueType' => 'key'],
            'filepath' => ['showType' => 'file', 'valueType' => 'callback', 'method' => 'getFilepath'],
            //'region' => ['valueType' => 'point', 'resource' => 'region'],
        ];
    }

    public function getFilepath($model, $field)
    {
        $data = $model->toArray();
        $data['filepath'] = $model->getFullFilepath();
        return [$data];
    }

    protected function _mimeTypeKeyDatas()
    {
        return [
            '' => '未知',
            'image/jpeg' => '图片JPG',
            'image/gif' => '图片GIF',
            'image/png' => '图片PNG',
            'application/zip' => '压缩文件',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'Excel-ext',
            'application/vnd.ms-excel' => 'Excel',
            'image/svg+xml' => 'SVG',
            'text/plain' => '文本文件',
            'video/mp4' => '视频Mp4',
            'audio/mpeg' => '音频Mpeg',
        ];
    }

    protected function _systemKeyDatas()
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
            'filepath' => ['width' => '180'],
        ];
    }

    protected function _pointOperations($model, $field)
    {
        $change = [
            'name' => '更换资源',
            'type' => 'popForm',
            'resource' => 'attributeValue',
            'app' => $this->getAppcode(),
            'params' => ['attribute_id' => $model->id],
        ];
        return [$change];
    }
}
