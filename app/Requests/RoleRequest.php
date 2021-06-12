<?php

declare(strict_types = 1);

namespace ModulePassport\Requests;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class RoleRequest extends AbstractRequest
{
    protected function _addRule()
    {
        return [
            'name' => ['bail', 'required'],
            'code' => ['bail', 'required', 'unique:auth_role,code'],
        ];
    }

    protected function _updateRule()
    {
        return [
            /*'code' => [
                //'bail', 
                //'required', 
                'unique:auth_role,code'
                //Rule::unique('auth_role')->ignore($this->routeParam('id', 0)),
            ],*/
            'name' => [],
            'description' => [],
        ];
    }


    public function attributes(): array
    {
        return [
            'name' => '角色名称'
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => '角色名称已存在',
        ];
    }

}
