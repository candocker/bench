<?php

declare(strict_types = 1);

namespace ModuleBench\Repositories;

class SpiderinfoRepository extends AbstractRepository
{
    protected function _sceneFields()
    {
        return [
            //'list' => ['id', 'name', 'code', 'url', 'status', 'db_info', 'point_operation'],
            'list' => ['id', 'name', 'code', 'url', 'status', 'point_operation'],//, 'db_info'
            'listSearch' => ['id', 'name'],
            'add' => ['name'],
            'update' => ['name'],
        ];
    }

    public function getShowFields()
    {
        return [
            //'sort' => ['valueType' => 'key'],
            'db_info' => ['valueType' => 'callback', 'method' => 'formatDbInfo'],
        ];
    }

    protected function _getTemplatePointFields()
    {
        return [
			'info_db' => ['type' => 'inline', 'formatView' => 'raw', 'method' => 'formatAttr'],
			//'status' => ['type' => 'changedown'],
			'listNo' => [
				'url', 'attachment_db', 'attachment_field', 'info_table', 'info_field',
			],
        ];
    }

	public function formatDbInfo($model)
	{
		$str = '';
		$fields = ['url', 'info_db', 'attachment_db', 'attachment_field', 'info_db', 'info_table', 'info_field'];
		foreach ($fields as $field) {
			$value = $field != 'url' ? $model->$field : "<a href='{$model->$field}' target='_blank'>{$model->$field}</a>";
			$str .= "<b>{$field}</b>: {$value}<br />";
		}
		return $str;
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

    protected function _pointOperations($model, $field)
    {
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

    public function _getFieldOptions()
    {
        return [
            'db_info' => ['name' => '目标数据信息', 'width' => '300px'],
        ];
    }
}
