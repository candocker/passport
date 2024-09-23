<?php

declare(strict_types = 1);

namespace ModulePassport\Models;

class ApplicationUser extends AbstractModel
{
    protected $table = 'auth_application_user';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function applicationInfo()
    {
        return $this->hasOne(Application::class, 'code', 'application_code');
    }

    public function createApplicationUserRecord($application, $user)
    {
        $data = ['application_code' => $application, 'user_id' => $user['id'], 'created_at' => date('Y-m-d H:i:s')];
        return $this->create($data);
    }
}
