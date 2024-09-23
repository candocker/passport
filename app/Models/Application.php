<?php

declare(strict_types = 1);

namespace ModulePassport\Models;

class Application extends AbstractModel
{
    protected $table = 'auth_application';
    public $incrementing = false;
    protected $primaryKey = 'code';
    protected $guarded = ['code'];

}
