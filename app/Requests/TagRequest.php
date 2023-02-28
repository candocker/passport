<?php

declare(strict_types = 1);

namespace ModulePassport\Requests;

class TagRequest extends AbstractRequest
{
    protected function _addRule()
    {
        return [
            'code' => [
                'bail',
                'required',
                'unique:infocms.position',
            ],
            'name' => ['bail', 'required'],
            'badge' => ['required', $this->_getKeyValues('badge')],
            'status' => ['required', $this->_getKeyValues('status')],
        ];
    }

    protected function _updateRule()
    {
        return [
            'code' => [
                'bail',
                'filled',
                $this->getRule()->unique('infocms.position')->ignore($this->routeParam('code', '')),
            ],
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
