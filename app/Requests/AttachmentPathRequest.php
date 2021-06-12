<?php

declare(strict_types = 1);

namespace ModulePassport\Requests;

class AttachmentPathRequest extends AbstractRequest
{
    protected function _addRule()
    {
        $params = $this->all();
        $rules = [
            'parent_id' => [
                'bail',
                'required',
                'exists:attachment_path,id',
                $this->getRule()->unique('attachment_path')->where(function ($query) use ($params){
                    $query->where('parent_id', $params['parent_id'])->where('path', $params['path']);
                }),
            ],
            'path' => ['bail', 'required'],
            'system' => ['bail', 'required', $this->_getKeyValues('system')],
            'status' => $this->_getKeyValues('status'),
            'type' => $this->_getKeyValues('type'),
            //'' => ['bail', 'required'],
        ];
        if ($params['parent_id'] === '0' || $params['parent_id'] === 0) {
            unset($rules['parent_id'][2]);
        } else {
            unset($rules['system']);
        }
        return $rules;
    }

    protected function _updateRule()
    {
        return [
            'id' => ['bail', 'required', 'exists'],
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
