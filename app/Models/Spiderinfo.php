<?php

declare(strict_types = 1);

namespace ModuleBench\Models;

class Spiderinfo extends AbstractModel
{
    protected $table = 'spiderinfo';
    protected $guarded = ['id'];

    public function getFile($pointFile = false)
    {
		if ($this->sort != 'single') {
			return '';
		}
        $fileName = $pointFile ?: $this->code;
		return "infos/{$this->site_code}/{$this->sort}/{$fileName}.html";
    }

	protected function _afterSaveOpe($infert, $changedAttributes)
	{
		if (in_array('status', array_keys($changedAttributes)) && $this->status == 100) {
			$this->getPointModel('commonlist')->updateAll(['status' => $this->status], ['spiderinfo_id' => $this->id]);
			$r = $this->getPointModel('commoninfo')->updateAll(['status' => $this->status], ['spiderinfo_id' => $this->id]);
			//$this->getPointMode('commininfo')->updateAll(['status' => $this->status], ['spiderinfo_id' => $this->id]);
		}
		return true;
	}

    protected function _getTemplatePointFields()
    {
        return [
			'extFields' => ['operation'],
			'info_db' => ['type' => 'inline', 'formatView' => 'raw', 'method' => 'formatAttr'],
			//'status' => ['type' => 'changedown'],
			'listNo' => [
				'url', 'attachment_db', 'attachment_field', 'info_table', 'info_field',
			],
        ];
    }

	public function formatAttr($view)
	{
		$str = '';
		$fields = ['url', 'info_db', 'attachment_db', 'attachment_field', 'info_db', 'info_table', 'info_field'];
		foreach ($fields as $field) {
			$value = $field != 'url' ? $this->$field : "<a href='{$this->$field}' target='_blank'>{$this->$field}</a>";
			$str .= "{$field}: {$value}<br />";
		}
		return $str;
	}

    public function formatOperation($view)
    {
		$str = '';
		$mParams = ['spiderinfo_id' => 'id', 'id' => 'id'];
		$extParams = ['target' => '_blank', 'seperator' => '<br />'];
		switch ($this->sort) {
		case 'single':
			$menuCodes = [
				'bench-spider_spiderinfo_operation' => ['name' => '本地Url', 'qStr' => 'action=local'],
                'bench-spider_spiderinfo_operation__rand' => ['name' => '页面处理', 'qStr' => 'action=deal-single'],
			];
            $str .= $this->_formatMenuOperation($view, $menuCodes, $mParams, $extParams) . '<br />';
			break;
		case 'list':
			$menuCodes = [
				'bench-spider_spiderinfo_operation' => ['name' => '写入列表', 'qStr' => 'action=record'],
                'bench-spider_commonlist_listinfo' => '',
                'bench-spider_commoninfo_listinfo' => '',
			    'bench-spider_spiderinfo_operation__rand' => ['name' => 'url检测', 'qStr' => 'action=record&check=1'],
			];
            $str .= $this->_formatMenuOperation($view, $menuCodes, $mParams, $extParams) . '<br />';
			break;
		}
		return $str;
    }

    protected function _dealContent($content, $aDatas)
    {
        if (empty($content)) {
            return '';
        }
        $replaces = [];
        foreach ($aDatas as $aData) {
            $sourceUrl = $aData['source_url_full'] ?? $aData['source_url'];
			$attachment = $this->_getAttachmentData($aData);
            $replaces[$sourceUrl] = $attachment->_getFile();
        }
		//print_r($replaces);
        $content = str_replace(array_keys($replaces), array_values($replaces), $content);
		return $content;
    }

    protected function _getAttachmentData($aData)
    {
		/*$baseField = ['name', 'source_id', 'source_url', 'info_table', 'info_field', 'info_id'];
		foreach ($baseFields as $bField) {
			if (isset($aData[$bField])) {
				return null;
			}
		}*/
        $info = array_merge($aData, [
            'spiderinfo_id' => $this->id,
            'source_site' => $this->site_code,
            'filename' => $aData['name'],
            'description' => $aData['name'],
            'created_at' => time(),
        ]);
        $attachment = $this->getPointModel('attachment-bench')->addInfoCheck($info, ['info_id', 'info_table', 'info_field', 'source_url']);
		return $attachment;
    }
}
