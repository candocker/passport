<?php

namespace ModulePassport\Resources;

class Resource extends AbstractResource
{

    protected function _keyvalueArray()
    {
        $keyField = $this->resource->getKeyName(); 
        return [              
            $keyField => $this->$keyField, 
            'name' => $this->name,
            'extField' => $this->app,
            'extField2' => $this->rtype,
        ];
    }
}
