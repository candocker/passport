<?php

declare(strict_types = 1);

namespace ModulePassport\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;
use ModulePassport\Models\Traits\TraitUser;

class User extends AbstractModel implements JWTSubject, AuthenticatableContract
{
    use Authenticatable;
    use TraitUser;

    //protected $connection = 'double6';
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $guarded = ['created_at', 'last_login_at', 'updated_at'];
    protected $fillable = [
        'name', 'email', 'mobile', 'password', 'sex', 'real_name', 'register_ip', 'nickname',
    ];

    protected $hidden = [
        'password', 'passowr_reste', 'pasport_empty', 'updated_at'
    ];

    protected $attributes = [
        'status' => 1,
    ];

    public static $sex = [
        0 => '未知',
        1 => '男',
        2 => '女',
    ];

    public function getSexTextAttribute()
    {
        return $this->attributes['sex_text'] = $this->getFormatState($this->attributes['sex'], self::$sex);
    }

    public function getStatusTextAttribute()
    {
        return $this->attributes['status_text'] = $this->getFormatState($this->attributes['status'], self::$status);
    }

    public function checkEnable()
    {
        return $this->status == 1;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'userData' => ['id' => $this->id, 'name' => $this->name],
        ];
    }

    public function afterSave()
    {
        $request = request();
        $applications = $request->input('application');
        if (!is_null($applications)) {
            $model = $this->getModelObj('applicationUser');
            $model->where('user_id', $this->id)->delete();
            foreach ($applications as $application) {
                $model->createApplicationUserRecord($application, $this);
            }
        }

        return true;
    }

    public function applicationUsers()
    {
        return $this->hasMany(ApplicationUser::class, 'user_id', 'id');
    }

    public function getApplication()
    {
        $applications = $this->applicationUsers;
        $result = ['source' => [], 'show' => ''];
        foreach ($applications as $applicationUser) {
            $result['source'][] = $applicationUser->applicationInfo['code'];
            $result['show'] .= ', ' . $applicationUser->applicationInfo['name'];
        }
        return $result;
    }
}
