<?php
declare(strict_types = 1);

namespace ModulePassport\Services;

use Illuminate\Hashing\BcryptHasher;
use Carbon\Carbon;
use Hyperf\Di\Annotation\Inject;

class UserPermissionService extends AbstractService
{
    /** 
     * @Inject                
     * @var BcryptHasher
     */
    protected $hash;

    protected $noRepository = true;

    /**
     */
    /*public function __construct()
    {
        parent::__construct();
        //$this->hash = new BcryptHasher();
        //$this->repository = $this->resource->getObject('repository', 'user-passport');
    }*/

    public function checkInput($inputs, $params)
    {
        $type = $params['type'];
        
    }

    public function getUserById($id)
    {
        $repository = $this->getRepositoryObj('user');
        $user = $repository->find($id);
        return $user;
    }

    public function getUser($inputs, $type, $addUser = false)
    {
        $repository = $this->getRepositoryObj('user');
        $field = $type == 'password' ? 'name' : 'mobile';
        $value = $type == 'password' ? $inputs['name'] : $inputs['mobile'];
        $user = $repository->findBy($field, $value);
        if (empty($user) && $type == 'mobile' && $addUser) {
            if (isset($inputs['password'])) {
                $inputs['password'] = $this->hash->make($inputs['password']);
            }
            $user = $repository->addUser($inputs);
        }

        if (empty($user) && $type == 'password') {
            $user = $repository->findBy('mobile', $value);
        }

        if (empty($user)) {
            return $this->throwException(400, "用户{$name}不存在");
        }
        if ($type == 'password' && !$this->hash->check($inputs['password'], $user->password)) {
            return $this->resource->throwException(400, '用户名或者密码错误');
        }

        $enable = $user->checkEnable();
        if (!$enable) {
            return $this->throwExceoption(405, "用户{$name}已禁用");
        }
        $user->recordSignin(['last_ip' => $this->resource->getIp()]);

        return $user;
    }

    public function getManager($user, $record = true)
    {
        $repository = $this->getRepositoryObj("manager");
        $manager = $repository->findBy('user_id', $user->id);
        if (empty($manager)) {
            return $this->throwException(400, "用户{$user['name']}不是管理员");
        }

        $enable = $manager->checkEnable();
        if (!$enable) {
            return $this->throwException(405, "用户{$user['name']}管理权限已禁用");
        }
        $manager->recordSignin(['last_ip' => $this->resource->getIp()]);
        return $manager;
    }

    public function getRolePermissions($manager)
    {
        $roleManagers = $manager->roleManagers;
        $permissionRepository = $this->getRepositoryObj('permission');
        $datas = [];
        foreach ($roleManagers as $roleManager) {
            $datas['roles'][] = $roleManager['role_code'];
            $datas['roleDetails'][$roleManager['role_code']] = $roleManager->role;
            $datas['permissions'][$roleManager['role_code']] = $permissionRepository->getTreeInfos($roleManager->role->getFormatPermissions());
            //echo $roleManager->role['name'] . '==' . count($datas['permissions'][$roleManager['role_code']]) . '---------';

        }
        return $datas;
    }

    public function getPointPermission($routeCode)//app, $path, $method)
    {
        return true;
    }

    public function checkPermissionTo($permission, $rolePermissions)
    {
        //return false;
        return true;
    }
}
/*trait EntranceTrait
{
	protected $postWithForm;
    public $mobile;
    public $nickname;
    public $name;
    public $email;
    public $password;
    public $user_template;
    public $password_confirm;
    public $code;
    public $captcha;
    public $remember_me = 3600 * 24;
    public $scene = '';

    public function scenarios()
    {
        return [
            'signin' => ['name', 'email', 'mobile', 'password', 'captcha'],
            'signup' => ['name', 'email', 'password', 'mobile', 'captcha', 'nickname', 'code'],
            'signupin' => ['mobile', 'captcha', 'code', 'nickname'],
        ];
    }

    public function entrance($action)
    {
        $this->setScenario($action);
        $this->postWithForm ? $this->load(Yii::$app->request->post()) : $this->load(Yii::$app->request->post(), '');
        $validate = $this->validate();
        $result = $validate ? $this->$action() : $this->_formatFailResult('登录失败，请您重试');
		return $result;
    }

    public function getNameField()
    {
        return 'mobile';
    }

	public function fsignin($mobile)
	{
		$userInfo = $this->getUserModel()->getInfo($mobile, 'mobile');
		return $this->signin($userInfo, true);
	}

    public function signin($userInfo = null, $force = false)
    {
        $userInfo = is_null($userInfo) ? $this->getUserInfo() : $userInfo;
        $loginResult = Yii::$app->user->login($userInfo, $this->remember_me);
        if (!$loginResult) {
            return ['status' => 400, 'message' => '登录失败'];
        }
		if (empty($force)) {
		    $userInfo = $userInfo->dealSignin();
		}

        $this->wrongTimes('clear');
		$token = Yii::$app->controller->haveToken() ? Yii::$app->user->identity->getAuthKey() : '';
		//$userPlat = $this->getPointModel('user-plat')->getInfoBySession();
		//if (!empty($userPlat)) {
			//$this->updateUserPlat($userPlat, $userInfo);
		//}
		$wechats = $this->getPointModel('wechat')->getInfos(['where' => ['sort' => 'wechat', 'status' => 1]]);
		$datas = [
			'token' => $token, 
			'userInfo' => $userInfo->restSimpleData($userInfo), 
			'userPlats' => $userInfo->getUserPlats($userInfo),
			'wechats' => $this->restSimpleDatas($wechats),
		];
        return ['status' => 200, 'message' => 'OK', 'datas' => $datas];
    }

    public function signupin()
    {
        $userInfo = $this->getUserInfo();
        if (empty($userInfo)) {
            $result = $this->signup(true);
            if (!$this->isResultOk($result)) {
                return $result;
            }
            $userInfo = $result['userInfo'];
        }
		if (!empty($this->nickname) && $this->nickname != $userInfo['nickname']) {
			$userInfo->nickname = $this->nickname;
			$userInfo->update(false, ['nickname']);
		}
		return $this->signin($userInfo);
    }

    public function signup($emptyPassword = false)
    {
		$data['password_empty'] = intval($emptyPassword);
		foreach (['mobile', 'password', 'name', 'nickname'] as $field) {
			$data[$field] = (string) strip_tags(trim($this->$field));
        }

        $model = $this->getUserModel();
        $result = $model->register($data);
        if (empty($result)) {
            return ['status' => 400, 'message' => '注册失败！'];
        }
        return ['status' => 200, 'message' => 'OK', 'userInfo' => $this->getUserInfo()];
    }

    public function wrongTimes($action) 
    {
        $session = Yii::$app->getSession();
        $session->open();
        $name = "_login_count";

        switch ($action) {
        case 'write':
            $count = isset($session[$name]) ? $session[$name] + 1: 1;
            $session[$name] = $count;
            return ;
        case 'check':
            $count = isset($session[$name]) ? $session[$name] : 0;
            return $count;
        case 'clear':
            if (isset($session[$name])) {
                unset($session[$name]);
            }
            return ;
        }
    }

    public function validatePassword($attribute, $params)
    {
        $user = $this->getUserInfo();
		if (!$user) {
            $this->addError($this->nameField, '用户不存在');
			return ;
		}

        if (!$user->validatePassword($this->password)) {
			$this->wrongTimes('write');
            $this->addError('password', '密码错误');
        }
    }

	protected $beforeData;
	protected $noRender;
	public $authOptional = ['signup', 'signin', 'signupin', 'logout'];
    public $appsort;

	public function beforeAction($action)
	{
		parent::beforeAction($action);
		$actionId = $action->id;
        $this->appsort = $this->getInputParams('appsort');
		if ($actionId == 'logout' || $actionId == 'check-auth') {
			return true;
		}
        if (!empty(Yii::$app->user->returnUrl)) {
            $this->setReturnUrl(Yii::$app->user->returnUrl, 'signin');
        }

        if (!Yii::$app->user->isGuest && $actionId != 'bind' && $actionId != 'fsignin') {
            header('Location: /');exit();
			return $this->goBack();
            //return Yii::$app->response->redirect($this->returnUrl)->send();
        }

		$this->beforeData = $this->beforeData($actionId);
		return true;
	}

	public function actionFsignin()
	{
		$token = $this->getInputParams('token');

        $key = '_merchant_force_signin';

		$tokenInfo = Yii::$app->session->get($key);
        Yii::$app->session->set($key, []);
		if (empty($tokenInfo)) {
		    $cacheKey = $key . $token;
		    $tokenInfo = Yii::$app->cache->get($cacheKey);
            Yii::$app->cache->set($cacheKey, []);
        }
		if (empty($tokenInfo) || $token != $tokenInfo['token']) {
			return $this->returnResult(['status' => 400, 'message' =>'非法请求']);
		}
		if (Yii::$app->params['currentTime'] - $tokenInfo['time'] > 500) {
			//exit('超时');
		}
		$mobile = $tokenInfo['mobile'];
		$result = $this->getModel()->fsignin($mobile);
		$result['pointUrl'] = Yii::getAlias('@adminurl/admin');
        return $this->returnResult($result);
		//if ($result['status'] != 200) {
            //return $this->returnResult($result);
		//}
        //header('Location: /');
	}

	public function actionBind()
	{
		$url = $this->getReturnUrl('signin');
		if (empty($url)) {
		    $url = $this->returnUrl;
		}
		$userPlat = $this->getPointModel('user-plat')->getInfoBySession();
		if (empty($userPlat)) {
			$authUrl = $this->isWechat ? $this->wechatAuthUrl : '/signin-qr.html';
			header("Location: {$authUrl}");
			exit();
		}
		$userInfo = $userPlat->getUserInfo();
		if (empty($userInfo)) {
			if (empty($this->userInfo)) {
		        return $this->render($this->viewPre . 'bind', ['backurl' => '/bind.html']);
			} else {
		        $r = $userPlat->updateUserId($this->userInfo['id']);	
			    header("Location: {$url}");
			}
		} else {
		    if (empty($this->userInfo) || !$userPlat->mapUserInfo($this->userInfo['id'])) {
			    $this->getModel()->signin($userInfo);
			    Yii::$app->session->remove('_session_returnurl');
			}

			header("Location: {$url}");
		}
	}

	protected function _entrance($action)
	{
		$data = $this->beforeData;
		if ($this->noRender) {
			return $data;
		}
		if ($data['status'] == 200) {
            $returnUrl = $this->getReturnUrl('signin');
            if (!empty($returnUrl)) {
                header("Location: {$returnUrl}");
                exit();
            }
			return $this->goBack();
		}
		$data = is_null($data) ? [] : $data;
        return $this->render($this->viewPre . $action, $data);
    }

    public function actionSigninQr()
    {
		$data = $this->beforeData;
		$data['wxLogin'] = $this->openLoginInfo($this->getOpenLoginParams());
        return $this->render($this->viewPre . 'signin-qr', $data);
    }

    protected function beforeData($action)
    {
		if ($this->checkAjax()) {
			$this->noRender = true;
			Yii::$app->response->format = Response::FORMAT_JSON; 
		}

        $model = $this->getModel();
		$datas = ['userInfo' => [], 'model' => $model, 'return_url' => $this->returnUrl];
        $result = ['status' => 400, 'message' => '', 'datas' => $datas];
        if ($this->isSubmit()) {
			$r = $model->entrance($action);
			if (isset($r['datas'])) {
				$r['datas']['return_url'] = $this->returnUrl;
			}
            $result = array_merge($result, $r);
        }

		return $result;
    }

	public function actionAuthWechat()
	{
		if ($this->isWechat) {
            $authUrl = $this->wechatAuthUrl;
		} else {
			$openParams = $this->getOpenLoginParams();
			$authUrl = '/signin.html';//$openParams ? '/signin-qr.html' : '/signupin.html';
		}
        header("Location: {$authUrl}");
        exit();
	}
}*/
