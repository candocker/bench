<?php

declare(strict_types = 1);

namespace ModuleBench\Controllers;

class SpiderinfoController extends AbstractController
{
    public function spiderPoint()
    {
        $file = $this->request->input('file');
        $service = $this->getServiceObj('spider');
        $service->spiderPoint($file);
        exit();
    }

    public function operation()
    {
        $repository = $this->getRepositoryObj();
        $info = $this->getPointInfo($repository, $this->request, false);
        //$actions = ['local', 'record', 'single'];
        $actions = ['record'];
        $action = $this->request->input('action');
        if (!in_array($action, $actions)) {
            $this->error('操作不存在');
        }

        if ($info->status == 100) {
            return ['type' => 'common', 'message' => '操作已锁定'];
        }

        /*if ($action == 'local') {
            $file = $this->spiderinfo->getFile($params['point_file'] ?? false);
            return $this->success(['type' => 'newPage', 'url' => $this->getConfig('uploadUrl', 'domains') . $file]);
        }*/

        $service = $this->getServiceObj('spider');
        $service->spiderinfo = $info;
        return $this->success($service->spiderinfoOperation($action, $this->request->all()));
    }

    public function spiderinfoOperation($action, $params)
    {
        $siteCode = $this->resource->strOperation($this->spiderinfo->site_code, 'studly');
        $code =  $this->resource->strOperation($this->spiderinfo->code, 'studly');
        $crawlerMethod = "_{$type}{$siteCode}{$code}";
        \Log::debug('spider-method-' . $crawlerMethod);
        if ($type == 'record') {
            $check = $this->params['check'] ?? false;
            return $this->$crawlerMethod($check);
        }
        $action = $this->resource->strOperation($action, 'camel');

        return $this->spiderinfoDeal($action, $params);
    }

    protected function _recordGuoxueGuoxue()
    {
        $datas = [
            //['page' => 1, 'name' => '诗经', 'code' => 'shijing', 'url' => 'http://www.guoxuemeng.com/guoxue/shijing/'],
            ['page' => 1, 'name' => '墨子', 'code' => 'mozi', 'url' => 'http://www.guoxuemeng.com/guoxue/mozi/'],
            ['page' => 1, 'name' => '荀子', 'code' => 'xunzi', 'url' => 'http://www.guoxuemeng.com/guoxue/xunzi/'],
            ['page' => 1, 'name' => '古文观止', 'code' => 'guwenguanzhi', 'url' => 'http://www.guoxuemeng.com/guoxue/guwenguanzhi/'],
        ];

        foreach ($datas as $data) {
            $data['firstUrl'] = str_replace('_{{PAGE}}', '', $data['url']);
            $this->_writeList($data['url'], $data['page'], $data);
        }
    }

    protected function _recordGushiwenGushiwen()
    {
        $datas = [
            ['page' => 1, 'name' => '楚辞', 'code' => 'chuci', 'url' => 'https://www.gushiju.net/shici/z-%E5%B1%88%E5%8E%9F'],
        ];

        foreach ($datas as $data) {
            $data['firstUrl'] = str_replace('_{{PAGE}}', '', $data['url']);
            $this->_writeList($data['url'], $data['page'], $data);
        }
    }

    protected function _writeList($urlBase, $pages, $params = [], $pointSpider = null)
    {
        for ($i = 1; $i <= $pages; $i++) {
            $url = ($i == 1 && isset($params['firstUrl'])) ? $params['firstUrl'] : $urlBase;
            $url = str_replace('{{PAGE}}', $i, $url);
            $rData = [
                'url' => $url,
                'code_ext' => isset($params['code']) ? $params['code'] : $i,
                'code' => $i,
                'name' => isset($params['name']) ? $params['name'] : $i,
                'page' => $i,
            ];
            //print_r($rData);
            //$spider = empty($pointSpider) ? $this : $pointSpider;
            $r = $this->getModelObj('commonlist')->createRecord($this->spiderinfo, $rData);
        }   
        return true;
    }
}
