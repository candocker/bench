<?php

declare(strict_types = 1);

namespace ModuleBench\Controllers;

class PageController extends AbstractController
{
    public function operation()
    {
        $action = $this->request->input('action');
        $actions = ['spiderPage', 'spiderAsset', 'down', 'dealPage', 'dealAsset'];
        if (!in_array($action, $actions)) {
            return $this->resource->throwException(404, '操作不存在');
        }
        $service = $this->getServiceObj('spiderPage');
        $pageId = $this->request->input('page_id');

        $infos = $this->getInfos($action, $pageId);
        echo count($infos);
        $method = "_{$action}Operation";
        foreach ($infos as $info) {
            $service->$method($info);
        }

        return $this->success();
    }

    public function getInfos($action, $pageId)
    {
        $query = in_array($action, ['spiderPage', 'dealPage']) ? $this->getModelObj('page') : $this->getModelObj('asset');
        $where = [];
        switch ($action) {
        case 'spiderAsset':
            $where = ['name_ext' => 'css'];//, 'status' => 1];
            break;
        case 'dealAsset':
            $where = ['name_ext' => 'css', 'status' => 2];
            break;
        case 'spiderPage':
        case 'down':
            $where = ['status' => 0];
            break;
        default:
            $where = ['status' => 1];
        }
        //$where = in_array($action, ['spiderPage', 'spiderAsset', 'down']) ? ['status' => 0] : ['status' => 1];
        $query = $query->where($where);
        if (!empty($pageId)) {
            $pageIds = array_filter(array_unique(implode(',', $pageId)));
            $query = $query->whereIn('id', $pageIds);
        }

        $infos = $query->get();
        return $infos;
    }

    /*public function getSubnavExt()
    {
        return [
            ['url' => $this->getMenuUrl('bench-spider_page_operation', ['action' => 'spider']), 'name' => '页面采集'],
            ['url' => $this->getMenuUrl('bench-spider_asset_operation', ['action' => 'spider']), 'name' => '二次采集'],
            ['url' => $this->getMenuUrl('bench-spider_asset_down'), 'name' => '资源下载'],
            ['url' => $this->getMenuUrl('bench-spider_page_operation', ['action' => 'deal']), 'name' => '页面本地化'],
            ['url' => $this->getMenuUrl('bench-spider_asset_operation', ['action' => 'deal']), 'name' => '二次本地化'],
        ];
    }*/
}
