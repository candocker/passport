<?php

declare(strict_types = 1);

namespace ModulePassport\Observers;

use ModulePassport\Models\Attachment;

class AttachmentObserver
{
    public function saving(Attachment $attachment)
    {
        $attachment->_beforeSave();
        return true;
    }

    public function deleted(Attachment $attachment)
    {
        $attachment->_afterDeleted();
        return true;
    }
}
