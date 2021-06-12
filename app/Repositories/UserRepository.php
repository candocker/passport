<?php
declare(strict_types = 1);

namespace ModulePassport\Repositories;

class UserRepository extends AbstractRepository
{
    public function getUser($condition)
    {
        return $this->model->where('id', '=', $condition)->first();
    }

    public function addUser($data)
    {
        $data['register_ip'] = $this->resource->getIp();
        $data['last_ip'] = $data['register_ip'];
        $data['last_at'] = time();
		//$this->gender = intval($this->gender);
	    //$this->spread_code = '';//$this->getSpreadCode('user');
        //return true;
		//$this->dealCoupon('signup', ['user_id' => $this->id]);
		//if (!empty($this->spread_code)) {
		    //$this->updateSpreadSuccess($this->spread_code, 'user', $this->id);
        //}
		/*if (!empty($this->fscene_show)) {
			$datas = explode(',', $this->fscene_show);
			foreach ($datas as $fscene) {
			    $this->getPointModel('fscene-user')->createRecord(['fscene_code' => $fscene, 'user_id' => $this->id, 'sort' => 'passport']);
			}
        }*/
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
		$userPlat = $this->getCacheOutData('third', 'userPlat', $model->id, 'user_id');
        return $userPlat;
    }

    public function getAvatar($model, $field)
    {
        /*$userPlat = $this->getUserPlat($model, $field);
        if (!empty($userPlat) && !empty($userPlat['headimgurl'])) {
            return $userPlat['headimgurl'];
        }*/
        return 'https://tmfile.oss-cn-beijing.aliyuncs.com/bf5a8b4b-08c7-48fd-8c7f-1eec27bcf469.jpg';
	}

    public function getUserData($user)
    {
        $resourceClass = $this->getResourceClass();
        $resource = new $resourceClass($user, 'view', $this, 1);

        return $resource->toArray();
    }
}

/*class User extends BaseModel implements IdentityInterface
{
	public $fscene_show;
	public $password_new;

	public function getMyFscenes()
	{
		$where = $this->isMerchant ? ['status' => 1, 'sort' => 'merchant'] : ['sort' => 'passport', 'status' => 1];
		$where = array_merge(['user_id' => $this->id], $where);
		$infos = $this->getPointModel('fscene-user')->getInfos(['where' => $where, 'indexBy' => 'fscene_code']);
		return $infos;
	}

	public function fsceneShow()
	{
		$myFscenes = $this->myFscenes;
		$str = '';
		foreach ($myFscenes as $myFscene) {
			$str .= $this->getPointName('fscene', ['code' => $myFscene['fscene_code']]) . ',';
		}
		return $str;
    }

    public function rules()
    {
        return [
			[['name', 'birthday', 'gender', 'nickname'], 'filter', 'filter' => 'strip_tags'],
			[['fscene_show', 'password_new'], 'safe'],
        ];
    }

	public function getChannelInfos()
	{
		$datas = [
			'default' => '站内',
		];

		return $datas;
	}

    protected function _beforeSaveOpe($insert)
    {
	}

	public function settingInfo($data)
	{
		$this->birthday = implode('-', [$data['year'], $data['month'], $data['day']]);
		$this->nickname = $data['nickname'];
		$this->gender = $data['gender'];
		return $this->update(false, $data);
	}

    protected function _getTemplatePointFields()
    {
        return [
            'is_validated' => ['type' => 'key'],
            'last_at' => ['type' => 'timestamp'],
			'fscene_show' => ['type' => 'inline', 'method' => 'fsceneShow'],
			'extFields' => ['fscene_show'],
			'listNo' => [
				'updated_at', 'email', 'liat_ip',// 'last_at',
				'role', 'tag', 'password', 'password_empty', 'auth_key',
				'signin_first', 'register_ip', 'login_num',
			],
            //'operation' => ['type' => 'operation'],
        ];
    }

	public function getUserPrivs()
	{
		return false;
	}

	public function getRolePrivs()
	{
		return false;
	}
}*/

/*trait TraitUserAuth
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

	public function getUserPlats()
	{
		$third = Yii::getAlias('@foundation/third', false);
		if (!$third) {
			return [];
		}
		$where = $this->isMerchant ? ['muser_id' => $this->id] : ['user_id' => $this->id];
		$infos = $this->getPointModel('user-plat')->getInfos(['where' => $where, 'indexBy' => 'plat_code']);
		$datas = [];
		foreach ($infos as $wCode => $info) {
			$wechat = $this->getPointModel('wechat')->getInfo($wCode, 'code');
			$data = $this->restSimpleData($info);
			$data['wechatInfo'] = $this->restSimpleData($wechat);
			$datas[$wCode] = $data;
		}
		return $datas;
	}

	public function getUserPlat($wCode = null)
	{
		if (is_null($wCode)) {
			$wCode = $this->getAppAttr('app')->params['currentWechatCode'];
		}
		$userPlats = $this->userPlats;
		return isset($userPlats[$wCode]) ? $userPlats[$wCode] : [];
	}
}*/
