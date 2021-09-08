<?php

declare(strict_types = 1);

namespace ModulePassport\Controllers;

class AttachmentPathController extends AbstractController
{

    public function path()
    {
        //$path = $this->
    }

    public function create()
    {
        $type = $this->request->input('type');
        $service = $this->getServiceObj('attachment');
        if ($type == 'local') {
            $createFile = $this->request->input('create_file', '');
            if ($createFile) {
                return $service->createFiles($type);
            }

            $path = $this->request->input('path', '');
            $result = $service->createPaths($type, $path);
            return $result;
        }

        $result = $service->createPathByRecord();
        return $result;
    }

    public function viewGeneral()
    {
        $repository = $this->getRepositoryObj();
        $request = $this->getPointRequest('', $repository);
        $info = $this->getPointInfo($repository, $request);
        $parentChains = $repository->getParentChains($info);
        $info['parentChains'] = $parentChains;
        return $this->success($info);
    }
}
