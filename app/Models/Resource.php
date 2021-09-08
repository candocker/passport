<?php

declare(strict_types = 1);

namespace ModulePassport\Models;

class Resource extends AbstractModel
{
    public $incrementing = false;
    protected $primaryKey = 'code';
    protected $table = 'auth_resource';
    //public $timestamps = false;
    //protected $fillable = ['app', 'code', 'name', 'controller', 'request', 'service', 'repository', 'resource', 'collection'];

    public function canDelete()
    {
        $exist = $this->getModelObj('permission')->where(['resource_code' => $this->code, 'app' => $this->app])->first();
        if ($exist) {
            return false;
        }
        return true;
    }
}
