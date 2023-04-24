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
        //$where['id'] = 1;
        $infos = $model->where($where)->orderBy('id', 'asc')->limit(300)->get();
        $service = $this->getServiceObj('spider');
        $result = [];
        foreach ($infos as $info) {
            $service->spiderinfo = $info->spiderinfo;
            $crawlerObj = $service->getCrawlerObj($info->getFile());
            if (empty($crawlerObj)) {
                continue;
            }

            if ($this->elem == 'list') {
                $customMethod = $info->getCustomMethod('whole');
                $method = method_exists($service, $customMethod) ? $customMethod : '_listDeal';
                $result = $service->$method($crawlerObj, $info);
                $commonlist = $info;
            } elseif ($this->elem == 'info') {
                $customMethod = $info->getCustomMethod('whole');
                $method = method_exists($service, $customMethod) ? $customMethod : '_infoDeal';
                $result = $service->$method($crawlerObj, $info);
            }

            $info->status = empty($result) ? 99 : 2;
            $info->save();
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
