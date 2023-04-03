<?php

declare(strict_types = 1);

namespace ModuleBench\Controllers;

class CommoninfoController extends AbstractController
{
    use DealBookTrait;

    public function operation()
    {
        $action = $this->request->input('action');
        if ($action == 'dealBook') {
            return $this->dealBookByInfo();
        }
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
        //$where['spiderinfo_id'] = 4;
        //$where['relate_id'] = 65;
        //$where['extfield'] = 65;
        $infos = $model->where($where)->orderBy('id', 'asc')->limit(300)->get();
        /*foreach ($infos as $info) {
            $sourceUrl = $info->source_url;
            $count = $model->where(['extfield' => 71, 'source_url' => $sourceUrl])->update(['status' => 11]);
            $count = $model->where(['extfield' => 71, 'source_url' => $sourceUrl])->count();
            echo $count . '=' . $sourceUrl . '<br />';
        }
        exit();*/


        //$infos = $model->where($where)->whereIn('id', [1335, 1344, 1345, 1351, 1353])->orderBy('id', 'asc')->limit(200)->get();
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
