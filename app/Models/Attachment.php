<?php
declare(strict_types = 1);

namespace ModulePassport\Models;

class Attachment extends AbstractModel
{
    protected $table = 'attachment';
    protected $fillable = ['path_id', 'name', 'tag', 'system', 'filepath', 'business', 'mime_type', 'size', 'filename', 'type', 'extension'];

    public function _beforeSave()
    {
        if ($this->path_id) {
            $path = $this->path;
            $this->system = $path->system;
        }
        if (empty($this->name)) {
            $this->name = str_replace(".{$this->extension}", '', $this->filename);
        }
        $this->filepath = ltrim($this->filepath, '/');
        return $this;
    }

    public function path()
    {
        return $this->hasOne(AttachmentPath::class, 'id', 'path_id');
    }

    public function getFullFilepath()
    {
        if (empty($this->filepath)) {
            return '';
        }
        $config = $this->config->get('local_params.attachment.system');
        $host = strval($config[$this->system]['host']);
        return rtrim($host, '/') . '/' . $this->filepath;
    }
}
