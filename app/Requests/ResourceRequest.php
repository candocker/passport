<?php

declare(strict_types = 1);

namespace ModulePassport\Requests;

class ResourceRequest extends AbstractRequest
{
    protected function _addRule()
    {
        $app = $this->input('app');
        return [
            'code' => [
                'bail',
                'required',
                $this->getRule()->unique('auth_resource')->where(function ($query) use ($app) {return $query->where('app', $app);})->ignore($this->routeParam('code', 0)),
            ],
            'name' => ['bail', 'required'],
            'app' => ['required', $this->_getKeyValues('app')],
        ];
    }

    protected function _updateRule()
    {
        return [
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
