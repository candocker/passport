<?php

declare(strict_types = 1);

namespace ModulePassport\Controllers;

class AttachmentController extends AbstractController
{
    public function addGeneral()
    {
        $repository = $this->getRepositoryObj();
        $request = $this->getPointRequest('create', $repository);
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
