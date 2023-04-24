<?php

declare(strict_types = 1);

namespace ModuleBench\Controllers;

class CommoninfoController extends AbstractController
{
    use OperationTrait;

    protected $elem = 'info';

    public function operation()
    {
        $action = $this->request->input('action');
        $actions = ['setting', 'spider', 'deal', 'dealBook'];
        if (!in_array($action, $actions)) {
            return $this->error('操作不存在');
        }
        $method = "_{$action}Operation";
        $model = $this->getModelObj('commoninfo');
        $datas = $this->$method($model, $this->request->all());
        return $this->success($datas);
    }
}
