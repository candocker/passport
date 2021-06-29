<?php

declare(strict_types = 1);

namespace ModulePassport\Controllers;

class AttachmentInfoController extends AbstractController
{
    public function add()
    {
        $repository = $this->getRepositoryObj();
        $request = $this->getRequestObj('add', $repository);

        $data = $request->getInputDatas('add');
        $attachmentIds = (array) $data['attachment_id'];
        unset($data['attachment_id']);
        $attachmentModel = $this->getModelObj('attachment');
        $message = '';
        foreach ($attachmentIds as $attachmentId) {
            if (empty($attachmentModel->find($attachmentId))) {
                continue;
            }
            $data['attachment_id'] = $attachmentId;
            $exist = $repository->getQuery()->where($data)->first();
            if (!empty($exist)) {
                continue;
            }
            $result = $repository->create($data);
        }
        return $this->success([]);
    }

}
