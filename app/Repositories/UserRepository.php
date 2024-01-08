<?php
declare(strict_types = 1);

namespace ModulePassport\Repositories;

use Swoolecan\Foundation\Helpers\CommonTool;

class UserRepository extends AbstractRepository
{
    public function getUser($condition)
    {
        return $this->model->where('id', '=', $condition)->first();
    }

    public function addUser($data)
    {
        $data['name'] = $data['name'] ?? CommonTool::generateUniqueString(10);
        $data['register_ip'] = $this->resource->getIp();
        $data['last_ip'] = $data['register_ip'];
        $data['last_at'] = time();
        return $this->create($data);
    }

    protected function _statusKeyDatas()
    {
        return [
            0 => '未激活',
            1 => '使用中',
            99 => '锁定',
        ];
    }

    protected function _sceneFields()
    {
        return [
            'list' => ['id', 'name', 'mobile', 'email', 'nickname', 'gender', 'birthday', 'spread_code', 'created_at', 'updated_at', 'signin_first', 'signin_num', 'last_ip', 'last_at', 'status', 'avatar'],
            'view' => ['id', 'name', 'mobile', 'email', 'nickname', 'gender', 'birthday', 'spread_code', 'created_at', 'updated_at', 'signin_first', 'signin_num', 'last_ip', 'last_at', 'status', 'avatar'],
            'listSearch' => ['id', 'name', 'mobile', 'nickname', 'created_at', 'last_at', 'status', 'spread_code'],
            'add' => ['nickname', 'name', 'mobile', 'email', 'gender', 'birthday', 'status'],
            'update' => ['nickname', 'gender', 'birthday', 'status'],
        ];
    }

    public function getFormFields()
    {
        return [
            'name' => ['type' => 'input', 'require' => ['add']],
            'mobile' => ['type' => 'input', 'require' => ['add']],
            'nickname' => ['type' => 'input', 'require' => ['add']],
            'gender' => ['type' => 'input'],
            'birthday' => ['type' => 'input'],
            'status' => ['type' => 'radio', 'infos' => $this->getKeyValues('status')],
        ];
    }

    public function getShowFields()
    {
        return [
            'avatar' => ['valueType' => 'callback', 'method' => 'getAvatar'],
            'userPlat' => ['valueType' => 'callback', 'method' => 'getUserPlat'],
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
        ];
    }

	public function getUserPlat($model, $field)
	{
        return [];
		$userPlat = $this->getRpcData('third', 'userPlat', $model->id, 'user_id');
        return $userPlat;
    }

    public function getAvatar($model, $field)
    {
        /*$userPlat = $this->getUserPlat($model, $field);
        if (!empty($userPlat) && !empty($userPlat['headimgurl'])) {
            return $userPlat['headimgurl'];
        }*/
        return 'http://ossfile.canliang.wang/book/common/2b3dc89a-569b-49f8-81d5-595fb9b95ba8.jpeg';
	}

    public function getUserData($user)
    {
        $resource = $this->getResourceObj($user, 'view', null, true);

        return $resource->toArray();
    }
}
