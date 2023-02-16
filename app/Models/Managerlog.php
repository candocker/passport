<?php

declare(strict_types = 1);

namespace ModulePassport\Models;

use ModulePassport\Models\Traits\TraitUser;

class Managerlog extends AbstractModel
{
    protected $table = 'auth_managerlog';
    //protected $fillable = ['nickname', 'user_id', 'status', 'signin_num', 'signin_first_at', 'last_at', 'last_ip'];

    protected $guarded = ['id'];
    public $timestamps = false;
    protected $dates = [
        'created_at',
    ];
}
