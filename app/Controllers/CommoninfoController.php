<?php

declare(strict_types = 1);

namespace ModuleBench\Controllers;

class CommoninfoController extends AbstractController
{
    use DealBookTrait;
    use OperationTrait;
    protected $elem = 'info';

    public function operation()
    {
        $action = $this->request->input('action');
        $actions = ['setting', 'spider', 'deal', 'dealBook'];
        if (!in_array($action, $actions)) {
            return $this->error('操作不存在');
        }
        $mothod = "_{$action}Operation";
        $model = $this->getModelObj('commoninfo');
        $datas = $this->$method($model, $this->request->all());
        return $this->success($datas);
    }

    protected function _otherOperation($params)
    {

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
        $where['list_id'] = 25;
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

    protected function _dealBookOperation($params)
    {
        $commonlistId = 65;
        $commonlist = $this->getModelObj('commonlist')->where('id', $commonlistId)->first();
        $bCode = $commonlist['code'];
        $infos = $this->getModelObj('commoninfo')->where(['relate_id' => $commonlistId])->orderBy('id', 'asc')->get();
        //$infos = $this->getModelObj('commoninfo')->where(['relate_id' => $commonlistId, 'status' => 2])->where('code_ext', '<>', '[]')->orderBy('id', 'asc')->get();
        $filePre = config('culture.material_path') . "/books/{$bCode}/";
        $chapterInfos = [];
        foreach ($infos as $info) {
            $chapterInfos[$info['extfield']][] = ['code' => $info['code'], 'name' => $info['name']];
            if (in_array($info['code'], ['0', '00'])) {
                continue;
            }
            $file = $filePre . $info['code'] . '.php';
            //$subInfos = $this->getModelObj('commoninfo')->where(['extfield' => $commonlistId, 'status' => 2, 'target_id' => $info['id']])->get();
            $subInfos = [$info];
            $str = $this->formatContent($subInfos);
            //echo $str;exit();
            if (!is_dir(dirname($file))) {
                mkdir(dirname($file));
            }
            file_put_contents($file, $str);
        }
        //print_r($chapterInfos);

        $this->createChapterFile($chapterInfos, $bCode);
        exit();
    }
}
