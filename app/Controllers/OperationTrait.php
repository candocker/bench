<?php

declare(strict_types = 1);

namespace ModuleBench\Controllers;

trait OperationTrait
{
    protected function _spiderOperation($model, $params)
    {
        $where = ['status' => 0];
        $infos = $model->where($where)->orderBy('id', 'asc')->limit(200)->get();
        $service = $this->getServiceObj('spider');
        $result = [];
        foreach ($infos as $info) {
            $service->spiderinfo = $info->spiderinfo;
            $service->spider($info, $this->elem);
        }
        return [];
    }

    protected function _dealOperation($model, $params)
    {
        $where = ['status' => 1];
        //$where['list_id'] = 25;
        $infos = $model->where($where)->orderBy('id', 'asc')->limit(300)->get();
        $service = $this->getServiceObj('spider');
        $result = [];
        foreach ($infos as $info) {
            $service->spiderinfo = $info->spiderinfo;
            $service->deal($info, $this->elem);
        }
		return $this->success();
    }

    protected function _deepDealOperation()
    {
        $where = ['status' => 1];
        //$where['spiderinfo_id'] = 39;
        $infos = $model->where($where)->limit(500)->get();
        //echo count($infos);exit();
        $service = $this->getServiceObj('spider');
        foreach ($infos as $info) {
            $service->spiderinfo = $info->spiderinfo;
            $service->$action($info, $this->elem);
        }
		return $this->success();
    }

    protected function _settingOperation($model, $params)
    {
        $elem = $this->request->input('elem');
        $updateData = $elem == 'spider' ? ['status' => 0] : ['status' => 1];
        $whereStatus = $elem == 'spider' ? [1, 2, 99] : [2, 99];
        $model->whereIn('status', $whereStatus)->update($updateData);
        return [];
    }
}
