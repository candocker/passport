<?php

declare(strict_types = 1);

namespace ModulePassport\Models\Traits;

trait TraitUser
{
    public $ignoreObserver = false;

    public function checkEnable()
    {
        return $this->status == 1;
    }

    public function recordSignin($data)
    {
        foreach ($data as $field => $value) {
            $this->$field = $value;
        }
        $this->last_at = date('Y-m-d H:i:s');
        $this->signin_num += 1;
        $this->signin_first = empty($this->signin_first) ? date('Y-m-d H:i:s') : $this->signin_first;
        $this->ignoreObserver = true;
        $this->save();
    }
}

