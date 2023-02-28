<?php

declare(strict_types = 1);

namespace ModulePassport\Models;

class TagInfo extends AbstractModel
{
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
            $exist = $this->where($data)->first();
            if ($exist) {
                $datas[] = $exist;
                continue;
            }
            $datas[] = $this->create($data);
        }
        return $datas;
    }
}
