<?php

declare(strict_types = 1);

namespace ModulePassport\Controllers;

//use yii\web\Response;
//use yii\helpers\ArrayHelper;
//use Overtrue\Pinyin\Pinyin;

class RegionController extends AbstractController
{
    public function cache()
    {
        $this->getRepositoryObj()->cacheDatas('region', 'origin');
        $this->getRepositoryObj()->cacheDatas('region', 'tree');
        return $this->success([]);
    }
}
