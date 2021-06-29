<?php

namespace ModulePassport\Services;

use Yii;
use yii\helpers\ArrayHelper;

class BaseapiService extends AbstractService
{
	public function mobileSignup($data)
	{
		$result = $this->checkCommon('mobile', $data['mobile']);
		$result = $this->isResultOk($result) ? $this->checkUser(['mobile' => $data['mobile']], 'signup') : $result;
		return $result;
	}

	public function getRegionInfo($pCode, $keyValue = false)
	{
		$infos = $this->getModelObj('region')->subInfos($pCode, false);
		$infos = $keyValue ? ArrayHelper::map($infos, 'code', 'name') : $infos;
		return ['status' => 200, 'message' => 'OK', 'data' => $infos];
	}

	public function getUserInfo($where)
	{
		$userInfo = $this->userModel->getInfo(['where' => $where]);
		return $userInfo;
	}

    public function checkUser($where, $type)
    {
        if (!in_array($type, ['signin', 'signup'])) {
            return ['status' => 200, 'message' => 'OK'];
        }

        $userInfo = $this->getUserInfo($where);
        if ($type == 'signin' && empty($userInfo)) {
            return ['status' => 400, 'message' => '用户不存在，请先注册'];
        }

        if ($type == 'signup' && !empty($userInfo)) {
            return ['status' => 400, 'message' => '用户已存在，请直接登录'];
        }

        return ['status' => 200, 'message' => 'OK'];
    }
}
