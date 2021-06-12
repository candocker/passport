<?php

declare(strict_types = 1);

namespace ModulePassport\Requests;

class AttachmentInfoRequest extends AbstractRequest
{
    protected function _updateRule()
    {
        return [
            'app' => ['bail', 'required', $this->_getKeyValues('app')],
            'info_table' => ['bail', 'required'],
            'info_id' => ['bail', 'required'],
            'info_field' => ['bail', 'required'],
            'attachment_id' => ['bail', 'required'],
        ];
    }

    public function attributes(): array
    {
        return [
            //'name' => '名称',
        ];
    }

    public function messages(): array
    {
        return [
            //'name.required' => '请填写名称',
        ];
    }
}
