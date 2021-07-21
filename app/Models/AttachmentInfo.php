<?php

declare(strict_types = 1);

namespace ModulePassport\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AttachmentInfo extends AbstractModel
{
    use SoftDeletes;

    protected $table = 'attachment_info';
    protected $fillable = ['name'];

    public function attachment()
    {
        return $this->hasOne(Attachment::class, 'id', 'attachment_id');
    }
}
