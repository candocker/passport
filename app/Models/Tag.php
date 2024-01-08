<?php

declare(strict_types = 1);

namespace ModulePassport\Models;

use Swoolecan\Foundation\Helpers\CommonTool;

class Tag extends AbstractModel
{
    protected $table = 'tag';
    protected $primaryKey = 'code';
    public $incrementing = false;
    public $timestamps = false;
    public $dates = ['created_at'];

    public function findCreate($name, $pointCode = '')
    {
        $info = $this->where(['name' => $name])->first();
        if (!empty($info)) {
            return $info;
        }

        $code = $pointCode ?: CommonTool::getSpellStr($name, '');
        $exist = $this->where(['code' => $code])->first();
        while ($exist) {
            $code .= rand(100, 999);
            $exist = $this->where(['code' => $code])->first();
        }

        $data = [
            'code' => $code,
            'name' => $name,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        return $this->create($data);
    }
}
