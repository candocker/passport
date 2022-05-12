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
        $filepathOld = $this->getOriginal('filepath');
        if ($this->filepath != $filepathOld) {
            $service = $this->getServiceObj('attachment');
            $service->deleteFiles($this->getOriginal('system'), $filepathOld);
        }
        return $this;
    }

    public function _afterDeleted()
    {
        $service = $this->getServiceObj('attachment');
        $service->deleteFiles($this->getOriginal('system'), $this->filepath);
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

    public function dispatchInfo($type)
    {
        $infoModel = $this->getModelObj('attachmentInfo');
        $exist = $infoModel->where('attachment_id', $this->id)->withTrashed()->first();
        if ($exist) {
            return '';
        }

        $method = "_{$type}Dispatch";
        return $this->$method();
    }

    protected function _bookDispatch()
    {
        $model = $this->getModelObj('culture-book');
        //$infos = $model->where('name', 'like', "%{$this->name}%")->get();
        $infos = $model->where('name', $this->name)->get();
        $filepath = $this->getFullFilepath();

        $count = count($infos);
        $this->extfield = $count;
        //$this->save();

        if (count($infos) < 1) {
            //print_r($this->toArray());
            echo "nnn-<a href='http://api.91zuiai.com/culture/test?method=checkFigure&code={$this->name}' target='_blank'>{$this->name}</a>==" . "<img src='{$filepath}' width='200px' height='200px' /><br />";
            return "SELECT * FROM `wp_book` WHERE `name` LIKE '%{$this->name}%';\n";
        }

        $sql = '';
        $pre = $count == 1 ? '' : 'mmm-';
        if ($count > 1) {
            //return ;
        }
        foreach ($infos as $info) {
            $authorName = $info->formatAuthorData();
            echo $pre . $info['code'] . '-=-' . $info['name'] . '==' . $authorName . '==' . $this->name . "<img src='{$filepath}' width='500px' height='500px' /><br />";
            $sql .= "{$pre}INSERT INTO `wp_attachment_info` (`attachment_id`, `app`, `info_table`, `info_field`, `info_id`) VALUES ('{$this->id}', 'culture', 'book', 'cover', '{$info['code']}');\n ";
        }
        $this->extfield = 'yyy';
        //$this->save();
        return $sql;
    }

    protected function _figureDispatch()
    {
        $model = $this->getModelObj('culture-figure');
        $infos = $model->where('name_card', $this->name)->get();

        $filepath = $this->getFullFilepath();
        $baseName = '';
        if (count($infos) < 1) {
            $baseName = substr($this->name, intval(strrpos($this->name, '·')));
            $baseName = str_replace('·', '', $baseName);
            $infos = $model->where(['name' => $baseName])->orWhere('name_card', $baseName)->get();
        }

        $count = count($infos);
        $this->extfield = $count;
        $this->save();

        if (count($infos) < 1) {
            //print_r($this->toArray());
            //echo 'nnn-' . '==' . $this->name . "<img src='{$filepath}' width='200px' height='200px' /><br />";
            return "SELECT * FROM `wp_figure` WHERE `name` LIKE '%{$baseName}%' OR `name_card` LIKE '%{$baseName}%';\n";
        }

        $sql = '';
        $pre = $count == 1 ? '' : 'mmm-';
        if ($count > 1) {
            //return ;
        }
        foreach ($infos as $info) {
            //echo $pre . $info['name'] . '==' . $info['card_name'] . '==' . $this->name . "<img src='{$filepath}' width='200px' height='200px' /><br />";
            $sql .= "{$pre}INSERT INTO `wp_attachment_info` (`attachment_id`, `app`, `info_table`, `info_field`, `info_id`) VALUES ('{$this->id}', 'culture', 'figure', 'photo', '{$info['code']}');\n ";
        }
        //$this->extfield = 'yyy';
        //$this->save();
        return $sql;
        // UPDATE `wp_attachment_info` AS `ai`, `wp_attachment` AS `a` SET `a`.`extfield` = 'yes' WHERE `ai`.`attachment_id` = `a`.`id`;
    }
}
