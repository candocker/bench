<?php

declare(strict_types = 1);

namespace ModuleBench\Repositories;

class SpiderinfoRepository extends AbstractRepository
{
    protected function _sceneFields()
    {
        return [
            'list' => ['id', 'name', 'code', 'site_code', 'sort', 'url', 'status', 'point_operation'],
            'listSearch' => ['id', 'name'],
            'add' => ['name'],
            'update' => ['name'],
        ];
    }

    public function getShowFields()
    {
        return [
            'sort' => ['valueType' => 'key'],
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

    protected function _pointOperations($model, $field)
    {
        if ($model->sort == 'single') {
            return [
                [
                    'name' => '本地URL',
                    'type' => 'api',
                    'resource' => 'spiderinfo',
                    'action' => 'operation',
                    'app' => $this->getAppcode(),
                    'params' => ['action' => 'local', 'id' => $model->id],
                ],
                [
                    'name' => '页面处理',
                    'type' => 'api',
                    'resource' => 'spiderinfo',
                    'action' => 'operation',
                    'app' => $this->getAppcode(),
                    'params' => ['action' => 'single', 'id' => $model->id],
                ],
            ];
        }
        return [
            /*[
                'name' => 'URL检测',
                'type' => 'api',
                'resource' => 'spiderinfo',
                'action' => 'operation',
                'app' => $this->getAppcode(),
                'params' => ['action' => 'record', 'check' => 1, 'id' => $model->id],
            ],*/
            [
                'name' => '写入列表',
                'type' => 'api',
                'resource' => 'spiderinfo',
                'action' => 'operation',
                'app' => $this->getAppcode(),
                'params' => ['action' => 'record', 'id' => $model->id],
            ],
            [
                'name' => '通用列表',
                'type' => 'newRoute',
                'resource' => 'commonlist',
                'action' => 'listinfo',
                'app' => $this->getAppcode(),
                'params' => ['spider_id' => $model->id],
            ],
            [
                'name' => '通用信息',
                'type' => 'newRoute',
                'resource' => 'commoninfo',
                'action' => 'listinfo',
                'app' => $this->getAppcode(),
                'params' => ['spider_id' => $model->id],
            ],
        ];

        return $lists;
    }

    protected function _statusKeyDatas()
    {
        return [
            0 => '录入',
            1 => '源采集',
            2 => '测试源处理',
            3 => '源处理完成',
			100 => '完成锁定',
        ];
    }

	protected function _sortKeyDatas()
	{
		return [
			'single' => '单页',
			'list' => '列表页',
			'show' => '内容页',
		];
	}
}
