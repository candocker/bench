<?php

declare(strict_types = 1);

namespace ModuleBench\Controllers;

class CommonlistController extends AbstractController
{
    use DealBookTrait;
    public function operation()
    {
        $action = $this->request->input('action');
        $model = $this->getModelObj('commonlist');
        if ($action == 'setting') {
            $force = $this->request->input('force');
            $updateData = $force == 'spider' ? ['status' => 0] : ['status' => 1];
            $statusValues = $force == 'spider' ? [1, 2, 99] : [2, 99];
            $model->whereIn('status', $statusValues)->update($updateData);
            return $this->success();
        }

        $where = $action == 'spider' ? ['status' => 0] : ['status' => 1];
        //$where['source_site'] = 'culture';
        //$where['spiderinfo_id'] = 39;
        //$where['id'] = 80;
        //$where = ['status' => 99, 'spiderinfo_id' => 5];
        //$infos = $model->where($where)->limit(500)->get();
        $infos = $model->whereIn('id', [71])->get();
        //echo count($infos);exit();
        $service = $this->getServiceObj('spider');
        foreach ($infos as $info) {
            if ($action == 'dealBook') {
                $this->dealBookByList($info);
                $info->status = 100;
                $info->save();
                continue;
            }
            $service->spiderinfo = $info->spiderinfo;
            $service->$action($info, 'list');
        }
		return $this->success();
    }
}
