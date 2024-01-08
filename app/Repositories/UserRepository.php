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

    protected function _sceneFields()
    {
        return [
            'list' => ['id', 'name', 'mobile', 'nickname', 'gender', 'birthday', 'created_at', 'signin_num', 'last_at', 'status'],
            'view' => ['id', 'name', 'mobile', 'nickname', 'gender', 'birthday', 'created_at', 'signin_num', 'last_at', 'status', 'avatar'],
            //'view' => ['id', 'name', 'mobile', 'email', 'nickname', 'gender', 'birthday', 'spread_code', 'created_at', 'updated_at', 'signin_first', 'signin_num', 'last_ip', 'last_at', 'status', 'avatar'],
            'listSearch' => ['id', 'name', 'mobile', 'nickname', 'created_at', 'status'],
            'keyvalueNameSearch' => ['keyword'],
            'add' => ['name', 'nickname', 'mobile', 'password', 'password_confirmation', 'gender', 'birthday', 'status'],
            'update' => ['name', 'nickname', 'mobile', 'password', 'password_confirmation', 'gender', 'birthday', 'status'],
            'changePassword' => ['password_old', 'password', 'password_confirmation'],
        ];
    }

    public function getFormFields()
    {
        return [
            'name' => ['type' => 'input', 'require' => ['add']],
            'mobile' => ['type' => 'input', 'require' => ['add']],
            'nickname' => ['type' => 'input', 'require' => ['add']],
            'gender' => ['type' => 'radio'],
            'status' => ['type' => 'radio', 'infos' => $this->getKeyValues('status')],
        ];
    }

    public function getShowFields()
    {
        return [
            'gender' => ['valueType' => 'key'],
            'avatar' => ['valueType' => 'callback', 'method' => 'getAvatar'],
            //'userPlat' => ['valueType' => 'callback', 'method' => 'getUserPlat'],
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
        //return 'https://xsjy-1254153797.cos.ap-shanghai.myqcloud.com/edu/courseware/pc/2023/09/13/wnmw2lvjkq.jpg';
        return 'http://ossfile.canliang.wang/book/common/2b3dc89a-569b-49f8-81d5-595fb9b95ba8.jpeg';
	}

    public function getUserData($user)
    {
        $resource = $this->getResourceObj($user, 'view', null, true);

        return $resource->toArray();
    }

    public function _getFieldOptions()
    {
        return [
        ];
    }

    public function addUser($data)
    {
        $data['name'] = $data['name'] ?? CommonTool::generateUniqueString(10);
        $data['register_ip'] = $this->resource->getIp();
        $data['last_ip'] = $data['register_ip'];
        $data['last_at'] = time();
        return $this->create($data);
    }

    public function _genderKeyDatas()
    {
        return [
            0 => '未知',
            1 => '男',
            2 => '女',
            99 => '保密',
        ];
    }

    protected function _statusKeyDatas()
    {
        return [
            0 => '未激活',
            1 => '使用中',
            99 => '锁定',
        ];
    }
}
