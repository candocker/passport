<?php

declare(strict_types = 1);

namespace ModulePassport\Controllers;

class AttachmentInfoController extends AbstractController
{
    public function addGeneral()
    {
        $repository = $this->getRepositoryObj();
        $request = $this->getPointRequest('add', $repository);

        $data = $request->getInputDatas('add');
        $attachmentIds = (array) $data['attachment_id'];
        unset($data['attachment_id']);
        $attachmentModel = $this->getModelObj('attachment');
        $attachmentInfoModel = $this->getModelObj('attachmentInfo');
        $attachmentInfoModel->where($data)->delete();
        $message = '';
        foreach ($attachmentIds as $attachmentId) {
            if (empty($attachmentModel->find($attachmentId))) {
                continue;
            }
            $data['attachment_id'] = $attachmentId;
            $exist = $attachmentInfoModel->where($data)->withTrashed()->first();
            if (!empty($exist)) {
                $exist->restore();
                continue;
            }
            $result = $repository->create($data);
        }
        return $this->success([]);
    }

}
