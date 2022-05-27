<?php

declare(strict_types = 1);

namespace ModuleBench\Repositories;

class NavsortInfoRepository extends AbstractRepository
{
    protected function _sceneFields()
    {
        return [
            'list' => ['id', 'navsort_code', 'info_id', 'website_name', 'status'],
            'listSearch' => ['navsort_code', 'website_name'],
            'add' => ['info_id', 'navsort_code', 'status'],
            'update' => ['info_id', 'navsort_code', 'status'],
        ];
    }

    public function getShowFields()
    {
        return [
            //'type' => ['valueType' => 'key'],
            'navsort_code' => ['showType' => 'cascader', 'valueType' => 'cascader', 'props' => ['value' => 'code', 'label' => 'name', 'children' => 'subInfos', 'checkStrictly' => false, 'multiple' => false], 'infos' => $this->getRepositoryObj('navsort')->getPointTreeDatas('navsort', 2, 'list')],
            'status' => ['showType' => 'select', 'valueType' => 'select'],
            'website_name' => ['valueType' => 'point', 'relate' => 'website', 'relateField' => 'nameFull'],
        ];
    }

    public function getSearchFields()
    {
        return [
            'navsort_code' => ['showType' => 'cascader', 'valueType' => 'cascader', 'props' => ['value' => 'code', 'label' => 'name', 'children' => 'subInfos', 'checkStrictly' => false, 'multiple' => false], 'infos' => $this->getRepositoryObj('navsort')->getPointTreeDatas('navsort', 2, 'list')],
            //'type' => ['type' => 'select', 'infos' => $this->getKeyValues('type')],
        ];
    }

    protected function _getFieldOptions()
    {
        return [
            'website_name' => ['name' => '站点名称'],
            'navsort_code' => ['width' => '200'],
        ];
    }

    public function getFormFields()
    {
        return [
            'info_id' => ['type' => 'selectSearch', 'require' => ['add'], 'searchResource' => 'website'],
            'navsort_code' => ['type' => 'cascader', 'props' => ['value' => 'code', 'label' => 'name', 'children' => 'subInfos', 'checkStrictly' => false, 'multiple' => false], 'infos' => $this->getRepositoryObj('navsort')->getPointTreeDatas('navsort', 2, 'list')],
            'status' => ['type' => 'radio', 'infos' => $this->getKeyValues('status')],
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
