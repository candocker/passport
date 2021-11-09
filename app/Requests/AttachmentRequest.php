<?php

declare(strict_types = 1);

namespace ModulePassport\Requests;

class AttachmentRequest extends AbstractRequest
{
    protected function _addRule()
    {
        $params = $this->all();
        $rules = [
            'path_id' => [
                'bail',
                'required',
                'exists:attachment_path,id',
            ],
            'filepath' => ['bail', 'required'],
            'system' => ['bail', 'required', $this->_getKeyValues('system')],
            'name' => ['bail', 'required'],
        ];
        if ($params['path_id'] === '0' || $params['path_id'] === 0) {
            unset($rules['path_id']);
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

    protected function _uploadRule()
    {
        $params = $this->all();
        $rules = [
            'path_id' => [
                'bail',
                'required',
                $this->getRule()->exists('attachment_path', 'id')->where(function ($query) {
                    $query->where('system', 'like', 'local%');
                }),
            ],
            'file' => ['bail', 'required', 'file'],
        ];
        if ($params['path_id'] === '0' || $params['path_id'] === 0) {
            unset($rules['path_id']);
        }
        return $rules;
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
