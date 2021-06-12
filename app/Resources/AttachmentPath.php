<?php

namespace ModulePassport\Resources;

use Framework\Baseapp\Resources\AbstractResource;

class AttachmentPath extends AbstractResource
{

    protected function _keyvalueArray()
    {
        $keyField = $this->resource->getKeyName(); 
        return [              
            $keyField => $this->$keyField, 
            'name' => "{$this->path} ({$this->system} {$this->name})",         
            'extField' => $this->system,
            'extField2' => $this->path_full,
        ];
    }
}
