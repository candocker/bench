<?php

declare(strict_types = 1);

namespace ModuleBench\Controllers;

class CommonlistController extends AbstractController
{
    use DealBookTrait;
    use OperationTrait;

    protected $elem = 'list';

    public function operation()
    {
        $action = $this->request->input('action');
        $actions = ['setting', 'spider', 'deal', 'deepdeal', 'dealBook'];
        if (!in_array($action, $actions)) {
            $this->error('操作不存在');
        }

        $method = "_{$action}Operation";
        $model = $this->getModelObj('commonlist');
        $datas = $this->$method($model, $this->request->all());
        return $this->success($datas);
    }

    protected function _dealBookOperation()
    {
    }
}
