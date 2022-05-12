<?php

declare(strict_types = 1);

namespace ModuleBench\Repositories;

class NavsortRepository extends AbstractRepository
{
    protected function _sceneFields()
    {
        return [
            'list' => ['name', 'id', 'code', 'parent_code', 'elemNum'],
            'listSearch' => ['id', 'name'],
            'add' => ['name'],
            'update' => ['name'],
        ];
    }

    public function _getFieldOptions()
    {
        return [
            'name' => ['width' => '200', 'align' => 'left'],
            'elemNum' => ['name' => '网站数量'],
        ];
    }

    public function getShowFields()
    {
        return [
            //'elemNum' => ['valueType' => 'callback', 'method' => 'getAvatar'],
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
