<?php

declare(strict_types = 1);

namespace ModuleBench\Controllers;

class CommoninfoController extends AbstractController
{
    public function operation()
    {
        $action = $this->request->input('action');
        $model = $this->getModelObj('commoninfo');
        if ($action == 'setting') {
            $force = $this->request->input('force');
            $updateData = $force == 'spider' ? ['status' => 0] : ['status' => 1];
            $statusValues = $force == 'spider' ? [1, 2, 99] : [2, 99];
            $model->whereIn('status', $statusValues)->update($updateData);
            return $this->success();
        }

        $where = $action == 'spider' ? ['status' => 0] : ['status' => 1];
        //$where['source_site'] = '';
        $where['spiderinfo_id'] = 3;
        $infos = $model->where($where)->orderBy('id', 'asc')->limit(200)->get();
        //$infos = $model->where('id', '>', 538)->where('id', '<', 800)->orderBy('id', 'asc')->limit(300)->get();
        //echo count($infos);exit();
        $service = $this->getServiceObj('spider');
        $result = [];
        foreach ($infos as $info) {
            $service->spiderinfo = $info->spiderinfo;
            $service->$action($info, 'info');
        }
		return $this->success();
    }
}
