<?php

declare(strict_types = 1);

namespace ModuleBench\Models;

class Attachment extends AbstractModel
{
    protected $table = 'attachment';
    protected $guarded = ['id'];

	public function createRecord($data, $spiderinfo, $targetId)
	{
        if (empty($spiderinfo->attachment_db) || empty($spiderinfo->attachment_field)) {
			return ;
		}
		$aFields = explode(',', $spiderinfo['attachment_field']);
		foreach ($aFields as $aField) {
			$aField = trim($aField);
			if (!isset($data[$aField])) {
				continue;
			}

			foreach ((array) $data[$aField] as $aUrl) {
				$where = [
                    'info_table' => $spiderinfo['info_table'],
                    'info_field' => $aField,
                    'info_id' => $targetId,
                    'source_url' => $aUrl,
				];
				$exist = $this->getInfo(['where' => $where]);
				if ($exist) {
					continue;
				}
                $aData = array_merge($where, [
                    'name' => $data['name'],
                    'spiderinfo_id' => $spiderinfo['id'],
                    'source_site' => $spiderinfo['site_code'],
                    'source_id' => $data['source_id'],
					'filename' => $data['name'],
					'description' => $data['name'],
					'created_at' => time(),
                ]);
                $this->addInfo($aData);
			}
		}
		return true;
	}

    public function spider()
    {
		if (empty($this->source_url)) {
			$this->source_status = 99;
			//print_r($this);
			//echo 'aaa';exit();
			$this->update(false, ['source_status']);
			return ;
		}
		//echo $this->_getFile() . '--' . $this->source_url ; exit();
        $result = $this->_downFile($this->_getFile(), $this->source_url);
		if (empty($result)) {
			$this->source_status = 88;
			$this->update(false, ['source_status']);
			return ;
		}

		$this->size = intval($result);
        $this->source_status = !empty($result) ? 1 : 99;
		$this->type = $this->getFileType($this->getPointFile($this->_getFile()));
		$this->extname = $this->getFileExt($this->source_url);
		$this->filepath = $this->_getFile();
		$this->in_use = 1;
        $this->update(false);
        return true;
    }

    public function _getFile()
    {
		$extName = $this->getFileExt($this->source_url);
		$file = "upload/{$this->source_site}/{$this->info_table}/{$this->info_field}/{$this->source_id}_{$this->id}.{$extName}";
		//echo $file;
		return $file;
    }

	public function getSourceStatusInfos()
	{
		return [
			'0' => '待采集',
			'1' => '已采集',
			'88' => '异常',
			'100' => '归档',
		];
	}

    protected function _getTemplatePointFields()
    {
        return [
			'type' => ['type' => 'common'],
            'source_url' => ['type' => 'inline', 'formatView' => 'raw', 'method' => 'formatSource'],
            //'status' => ['type' => 'changedown'],
            'listNo' => [
                'updated_at', 
            ],
        ];
    }

    public function formatSource($view)
    {
        $str = "<a href='{$this->source_url}' target='_blank'>{$this->source_url}</a><br />";
		$file = $this->_getFile();
        if ($this->fileExist($file)) {
            $localUrl = $this->getLocalUrl($file);
            $str .= "<a href='{$localUrl}' target='_blank'>{$localUrl}</a><br />";
        } else {
            $str .= '没有本地文件';
        }

        return $str;
    }
}
