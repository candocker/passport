<?php

declare(strict_types = 1);

namespace ModulePassport\Controllers;

class AttachmentController extends AbstractController
{
    public function add()
    {
        $repository = $this->getRepositoryObj();
        $request = $this->getRequestObj('create', $repository);
        $scene = $request->input('point_scene');
        if ($scene == 'get_formelem') {
            return $this->success(['formFields' => $repository->getFormatFormFields('add'), 'fieldNames' => $repository->getAttributeNames('add')]);
        }

        $data = $request->getInputDatas('create');
        $result = $repository->create($data);
        $data = $result->toArray();
        $data['filepath'] = $result->getFullFilepath();
        return $this->success($data);
    }

    public function upload()
    {
        $repository = $this->getRepositoryObj();
        $request = $this->getRequestObj('upload', $repository);

        $pathId = $request->input('path_id');
        $path = '/';
        $system = $request->input('system', '');
        if (!empty($pathId)) {
            $pathInfo = $this->getRepositoryObj('attachmentPath')->find($pathId);
            $path = $pathInfo['path_full'];
            $system = $pathInfo['system'];
        }
        $service = $this->getServiceObj('attachment');
        $fileData = $service->saveFile($system, $path, $this->request->file('file'));
        $fileData['path_id'] = $pathId;
        $info = $repository->create($fileData);
        $data = $info->toArray();
        $data['filepath'] = $info->getFullFilepath();
        return $this->success($data);
    }
}

/*{
	//use TraitBase;
	public $attachmentCode;

	public function init()
	{
		parent::init();
		$attachmentCode = $this->getInputParams('attachment_code');
		if (empty($attachmentCode)) {
			exit('参数有误');
		}
		$this->attachmentCode = $attachmentCode;
    }

	public function actions()
	{
		return array_merge(parent::actions(), [
            'upeditor' => [
                'class' => 'common\ueditor\UEditorAction',
            ],  
		]);
	}

    public function getAttachment($params = [])
    {
        //return $this->getPointModel($this->attachmentCode, true, $params);
		$modelMid = $this->getPointModel('attachment');
		$model = $modelMid->initMark($this->attachmentCode);
		if (!empty($params)) {
			foreach ($params as $field => $value) {
				$model->$field = $value;
			}
		}
		return $model;
    }

    public function actionIndex()
    {
        error_reporting(0);//E_ALL);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $table = $this->getInputParams('table');
        $field = $this->getInputParams('field');
        $id = $this->getInputParams('id');
        if (empty($table) || empty($field)) {
            return [];
        }

        //$_FILES = Yii::$app->params['uploadTest'];
        $params = [
            'info_table' => $table,
            'info_field' => $field,
            'info_id' => intval($id),
        ];
        $action = $this->getInputParams('action');
        if ($action == 'show') {
            return $this->getInfos($params);
        }

        $model =  $this->getAttachment($params);
        $files = UploadedFile::getInstances($model, 'files');
        $model->file = isset($files[0]) ? $files[0] : null;
        if ($model->save()) {
            $baseName = substr($model->name, 0, strrpos($model->name, '.'));
            $data = [
                'status' => '200200',
                'id' => $model->id,
                'name' => $model->name,
                'size' => $model->size,
                'filename' => $baseName,
                'orderlist' => 0,
                'description' => $baseName,
                'url' => $model->getUrl(),
            ];
        } else {
            $message = array_pop($model->getFirstErrors());
            $data = [
                'status' => '400400',
                'message' => $message,
            ];
        }
        $data = ['files' => [$data]];
        return $data;
    }

    public function actionShow($id)
    {
        $model = $this->getAttachment()->getInfo($id);;
        $response = Yii::$app->getResponse();
		$path = $model->path;
		$isDown = $this->getInputParams('download');
		$params = [
            'mimeType' => $model->type,
            'fileSize' => $model->size,
            'inline' => $isDown ? false : true
        ];
        return $response->sendFile($path, $model->name, $params);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['cors'] = $this->_corBehavior();
		return $behaviors;
	}
}*/
