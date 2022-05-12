<?php

declare(strict_types = 1);

namespace ModuleBench\Repositories;

class ToolsortRepository extends AbstractRepository
{
    protected function _sceneFields()
    {
        return [
            'list' => ['name', 'id', 'code', 'parent_code'],
            'listSearch' => ['id', 'name'],
            'add' => ['name'],
            'update' => ['name'],
        ];
    }

    public function _getFieldOptions()
    {
        return [
            'name' => ['width' => '200', 'align' => 'left'],
        ];
    }

    public function getShowFields()
    {
        return [
            //'type' => ['valueType' => 'key'],
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
