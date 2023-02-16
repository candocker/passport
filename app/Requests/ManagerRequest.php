<?php

declare(strict_types = 1);

namespace ModulePassport\Requests;

class ManagerRequest extends AbstractRequest
{
    protected function _addRule()
    {
        return [
            'nickname' => ['bail', 'string', 'required', 'unique:auth_manager,nickname', 'between:3,20'],
            'user_id' => ['bail', 'required', 'unique:auth_manager,user_id', 'exists:user,id'],
            'status' => [$this->_getKeyValues('status')],
        ];
    }

    protected function _updateRule()
    {
        return [
            //'nickname' => ['string|between:6,20'],
            'nickname' => ['bail', 'filled', 'string', 'unique:auth_manager,nickname', 'between:3,20'],
            'status' => ['numeric', $this->_getKeyValues('status')],
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

    public function filterDirtyData($data)
    {
        foreach (['role'] as $field) {
            if (isset($data[$field])) {
                unset($data[$field]);
                $this->allowEmpty = true;
            }
        }
        return $data;
    }
}
