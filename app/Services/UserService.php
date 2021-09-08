<?php

namespace ModulePassport\Services;

class UserService extends AbstractService
{
	public $isMerchant;
    public static function findByName($name)
    {
        return static::findOne(['name' => $name]);//, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
		if (empty($token)) {
			return null;
		}
        return static::findOne(['auth_key' => $token]);
    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            //'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        $result = Yii::$app->security->validatePassword($password, $this->password);
        return $result;
    }

    public function setPassword($password, $passwordField = 'password')
    {
        $this->$passwordField = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

	public function dealSignin()
	{
        $this->last_at = Yii::$app->params['currentTime'];
        $this->last_ip = $this->ipInfo['ip'];
        $this->signin_num = $this->signin_num + 1;
		if (empty($this->signin_first)) {
			$this->signin_first = Yii::$app->params['currentTime'];
			$this->status = empty($this->status) ? 'common' : $this->status;
		}
        $this->generateAuthKey();
        $this->update(false);
		return $this;
	}

	public function registerByPlat($platData)
	{
		$data = [
			'password' => rand(1, 10),
			//'name' => $platData['name'],
            'auth_key' => Yii::$app->security->generateRandomString(),

			'nickname' => $platData['nickname'],
		];
		return $this->register($data);
	}

	public function register($data)
	{
		$data['name'] = empty($data['name']) ? $this->getUniqidId() : $data['name'];
		$model = new self($data);
		$r = $model->insert(false);
		return empty($r) ? false : $model;
	}

	public function getPasswordStr()
	{
		if (!isset($this->password_empty)) {
			return '修改密码';
		}
        return $this->password_empty ? '设置密码' : '修改密码';
	}

	public function getAvatar()
	{
		$userPlat = $this->userPlat;
		$avatar = !empty($userPlat) && !empty($userPlat['headimgurl']) ? $userPlat['headimgurl'] : '';
		return $avatar ? $avatar : Yii::getAlias('@staticurl') . '/common/images/avatar.jpg';
	}
}
