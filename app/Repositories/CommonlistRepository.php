<?php

declare(strict_types = 1);

namespace ModuleBench\Repositories;

class CommonlistRepository extends AbstractRepository
{
    protected function _sceneFields()
    {
        return [
            'list' => ['id', 'name', 'spiderinfo_id', 'code', 'spider_num', 'source_site', 'source_url', 'status'],
            'listSearch' => ['id', 'name'],
            'add' => ['name'],
            'update' => ['name'],
        ];
    }

    public function getShowFields()
    {
        return [
            'source_url' => ['valueType' => 'link', 'showName' => '源URL'],
        ];
    }

    public function getSearchFields()
    {
        return [
            //'type' => ['type' => 'select'],
        ];
    }

    public function getFormFields()
    {
        return [
            //'type' => ['type' => 'select'],
        ];
    }

    protected function _statusKeyDatas()
    {
        return [
            0 => '记录',
            1 => '采集',
            2 => '处理',
			98 => '撤出',
            99 => '异常',
			100 => '完成锁定',
        ];
    }
}
