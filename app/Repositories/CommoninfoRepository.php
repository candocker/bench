<?php

declare(strict_types = 1);

namespace ModuleBench\Repositories;

class CommoninfoRepository extends AbstractRepository
{
    protected function _sceneFields()
    {
        return [
            'list' => ['id', 'spiderinfo_id', 'code', 'code_ext', 'source_site', 'source_url', 'status', 'created_at'],
            'listSearch' => ['id', 'name', 'spiderinfo_id'],
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
            //'type' => ['type' => 'select', 'infos' => $this->getKeyValues('type')],
        ];
    }

    public function getFormFields()
    {
        return [
            //'type' => ['type' => 'select', 'infos' => $this->getKeyValues('type')],
        ];
    }

    protected function _statusKeyDatas()
    {
        return [
            0 => '未激活',
            1 => '使用中',
            99 => '锁定',
        ];
    }
}
