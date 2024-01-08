<?php

namespace ModulePassport\Resources;

class UserCollection extends AbstractCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array
     */
    /*public function toArray() :array
    {
        return [
            'data' => $this->collection,
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }*/

    protected function _keyvalueNameArray()
    {
        return [
            'key' => 'name',
            'name' => 'name',
            'extField' => 'extField',
            'extField2' => 'extField2',
            'data' => $this->collection->toArray(),
        ];
        $result = [];
        foreach ($datas as $data) {
            $tmp = array_values($data);
            $result[$tmp[0]] = $tmp[1];
        }
        return ['data' => $result];
    }
}
