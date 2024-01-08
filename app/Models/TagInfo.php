<?php

declare(strict_types = 1);

namespace ModulePassport\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class TagInfo extends AbstractModel
{
    use SoftDeletes;
    protected $table = 'tag_info';
    public $timestamps = false;

    public function tag()
    {
        return $this->hasOne(Tag::class, 'code', 'tag_code');
    }

    public function createTagInfos($params)
    {
        $baseData['app'] = $params['app'];
        $baseData['info_table'] = $params['info_table'];
        $baseData['info_id'] = $params['info_id'];
        $datas = [];
        foreach ($params['tags'] as $tagCode) {
            $tag = $this->getModelObj('tag')->where(['code' => $tagCode])->first();
            if (empty($tag)) {
                continue;
            }
            $data = array_merge($baseData, [
                'tag_code' => $tagCode,
            ]);
            $exist = $this->where($data)->withTrashed()->first();
            if ($exist) {
                $exist->restore();
                $datas[] = $exist;
                continue;
            }
            $datas[] = $this->create($data);
        }
        return $datas;
    }

    public function getTargetInfo()
    {
        $model = $this->getModelObj("{$this->app}-{$this->info_table}");
        $info = $model->find($this->info_id);
        return $info;
    }

    public function getDatas($params)
    {
        $infos = $this->where($params)->get();
        $datas = [];
        foreach ($infos as $info) {
            $data = $info->toArray();
            $data['name'] = $info->tag->name;
            $datas[] = $data;
        }
        return $datas;
    }
}
