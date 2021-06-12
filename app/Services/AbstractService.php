<?php
declare(strict_types = 1);

namespace ModulePassport\Services;

use Framework\Baseapp\Services\AbstractService as AbstractServiceBase;

abstract class AbstractService extends AbstractServiceBase
{
    protected function getAppcode()
    {
        return 'passport';
    }
}
