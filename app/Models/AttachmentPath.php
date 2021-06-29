<?php

declare(strict_types = 1);

namespace ModulePassport\Models;

class AttachmentPath extends AbstractModel
{
    protected $table = 'attachment_path';
    protected $fillable = ['parent_id', 'path', 'path_full', 'name', 'status', 'system', 'tag', 'business', 'type'];
    //public $timestamps = false;

    public function parentPath()
    {
        return $this->hasOne(AttachmentPath::class, 'id', 'parent_id');
    }

    protected function _beforeSave()
    {
        $parent = $this->parentPath;
        $this->path_full = $parent['path_full'] . '/' . $this->path;
        $this->system = !empty($parent) ? $parent['system'] : $this->system;
        $this->createPath();
        return $this;
    }

    public function createPath()
    {
        if (substr($this->system, 0, 5) != 'local') {
            return ;
        }

        $service = $this->getServiceObj('attachment');
        $r = $service->mkdirLocal($this->system, $this->path_full);
        return true;
    }
}
