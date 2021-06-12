<?php

declare(strict_types = 1);

namespace ModulePassport\Models;

class AttachmentInfo extends AbstractModel
{
    protected $table = 'attachment_info';
    protected $fillable = ['name'];

    public function attachment()
    {
        return $this->hasOne(Attachment::class, 'id', 'attachment_id');
    }
}
