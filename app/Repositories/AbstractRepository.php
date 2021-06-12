<?php
declare(strict_types = 1);

namespace ModulePassport\Repositories;

use Framework\Baseapp\Repositories\AbstractRepository as AbstractRepositoryBase;

class AbstractRepository extends AbstractRepositoryBase
{
    public function getDefaultShowFields()
    {
        return array_merge(parent::getDefaultShowFields(), [
            //'user_id' => ['valueType' => 'common'],
        ]);
    }

    protected function getAppcode()
    {
        return 'passport';
    }
}
