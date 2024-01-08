<?php
declare(strict_types = 1);

namespace ModulePassport\Repositories;

class ManagerRepository extends AbstractRepository
{
    protected function _sceneFields()
    {
        return [
            //'list' => ['id', 'nickname', 'user_id', 'mobile', 'role', 'created_at', 'updated_at', 'signin_first', 'signin_num', 'last_ip', 'last_at', 'status'],
            'list' => ['id', 'name', 'nickname', 'mobile', 'role', 'gender', 'created_at', 'signin_num', 'last_at', 'status', 'password', 'password_confirmation'],
            'listSearch' => ['id', 'name', 'nickname', 'mobile', 'created_at', 'status'],
            'add' => ['name', 'role', 'status', 'nickname', 'mobile', 'password', 'password_confirmation', 'gender', 'birthday'],
            'update' => ['name', 'role', 'status', 'nickname', 'mobile', 'password', 'password_confirmation', 'gender', 'birthday'],
        ];
    }

    public function getFormFields()
    {
        return [
            //'name' => ['type' => 'selectSearch', 'searchApp' => 'passport', 'searchResource' => 'user', 'allowCustom' => 1],
            'name' => ['type' => 'selectSearch', 'searchApp' => 'passport', 'searchResource' => 'user', 'pointScene' => 'keyvalueName', 'allowCustom' => 1],
            'nickname' => ['type' => 'input', 'require' => ['add']],
            'user_id' => ['type' => 'selectSearch', 'require' => ['add'], 'searchResource' => 'user'],
            'role' => ['type' => 'select', 'infos' => $this->getPointKeyValues('role'), 'multiple' => 1],
            'status' => ['type' => 'radio', 'infos' => $this->getKeyValues('status')],
            'gender' => ['type' => 'radio', 'infos' => $this->getKeyValues('gender')],
        ];
    }

    public function getShowFields()
    {
        return [
            'role' => ['valueType' => 'extinfo', 'extType' => 'role'],
            'gender' => ['valueType' => 'key'],
        ];
    }

    public function getSearchFields()
    {
        return [
            'user_id' => ['type' => 'input'],
            'last_at' => ['type' => 'datetimerange'],
        ];
    }

    public function getSearchDealFields()
    {
        return [
            'name' => ['ignore' => true],
            'nickname' => ['operator' => 'like'],
            'mobile' => ['ignore' => true],
            //'user_id' => ['type' => 'relate', 'elem' => 'user', 'operator' => 'like', 'field' => 'name'],
        ];
    }

    public function _getFieldOptions()
    {
        return [
            'name' => ['width' => '120', 'name' => '账号'],
            'gender' => ['width' => '120', 'name' => '性别'],
            'signin_num' => ['width' => '60'],
            'nickname' => ['width' => '120'],
            'role' => ['name' => '角色', 'width' => '120'],
            'mobile' => ['name' => '手机号', 'width' => '120'],
            'user_id' => ['width' => '100'],
            'last_ip' => ['width' => '120'],
        ];
    }

    protected function _genderKeyDatas()
    {
        return $this->getRepositoryObj('user')->_genderKeyDatas();
    }

    protected function _statusKeyDatas()
    {
        return [
            '0' => '未激活',
            '1' => '使用中',
            '99' => '锁定',
        ];
    }

    protected function applyCriteria()
    {
        $params = request()->all();
        if (isset($params['mobile']) && !empty($params['mobile'])) {
            $this->model = $this->model->whereHasIn('user', function($query) use ($params) {
                $query = $query->where('mobile', 'like', "%{$params['mobile']}%");
            });
        }
        if (isset($params['name']) && !empty($params['name'])) {
            $this->model = $this->model->whereHasIn('user', function($query) use ($params) {
                $query = $query->where('name', 'like', "%{$params['name']}%");
            });
        }
        //echo $this->model->toSql();exit();
        return parent::applyCriteria();
    }
}
