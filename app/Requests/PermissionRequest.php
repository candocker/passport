<?php

declare(strict_types = 1);

namespace ModulePassport\Requests;

class PermissionRequest extends AbstractRequest
{

    protected function _addRule()
    {
        return [
            'code' => [
                'bail',
                'required',
                $this->getRule()->unique('auth_permission')->ignore($this->routeParam('id', 0)),
            ],
            'resource_code' => ['bail', 'filled', 'exists:auth_resource,code'],
            'parent_code' => ['bail', 'filled', 'exists:auth_permission,code'],
            'name' => ['bail', 'required'],
            'app' => ['required', $this->_getKeyValues('app')],
            'method' => [$this->_getKeyValues('method')],
            'display' => [$this->_getKeyValues('display')],
        ];
    }

    protected function _updateRule()
    {
        return [
            'parent_code' => ['bail', 'nullable', 'exists:auth_permission,code'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '权限名称'
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => '权限名称已存在',
        ];
    }

}
