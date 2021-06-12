<?php

declare(strict_types = 1);
/**
 * This abstract controller.
 *
 * @link     http://www.canliang.wang
 * @document http://wiki.canliang.wang
 * @contact  iamwangcan@gmail.com
 * @license  https://github.com/swoolecan/hyperf-baseapp/blob/master/LICENSE.md
 */

namespace ModulePassport\Controllers;

use Framework\Baseapp\Controllers\AbstractController as AbstractControllerBase;

abstract class AbstractController extends AbstractControllerBase
{

    protected function getAppcode()
    {
        return 'passport';//$this->config->get('app_code');
    }
}
