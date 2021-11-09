<?php

declare(strict_types = 1);

namespace ModulePassport\Observers;

use ModulePassport\Models\AttachmentPath;

class AttachmentPathObserver
{
    public function saving(AttachmentPath $attachmentPath)
    {
        return true;
        return $attachmentPath->_eventSaving();
    }
}
