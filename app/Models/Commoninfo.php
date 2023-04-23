<?php

declare(strict_types = 1);

namespace ModuleBench\Models;

class Commoninfo extends AbstractModel
{
    protected $table = 'commoninfo';
    protected $guarded = ['id'];


    public function getCustomMethod($type)
    {
        $commonlist = $this->commonlist;
        $method = '_info' . ucfirst($this->spiderinfo['code']) . ucfirst($commonlist->code) . ucfirst($type);
        return $method;
    }

    public function commonlist()
    {
        return $this->hasOne(Commonlist::class, 'id', 'list_id');
    }

    public function spiderinfo()
    {
        return $this->hasOne(Spiderinfo::class, 'id', 'spiderinfo_id');
    }

    public function getTargetInfo()
    {
        $info = $this->spiderinfo->getTargetModel()->find($this->target_id);
    }

    public function createRecord($data, $spiderinfo)
    {
		$where = ['spiderinfo_id' => $spiderinfo['id'], 'source_id' => $data['source_id'], 'source_url' => $data['source_url']];
        $exist = $this->where($where)->first();
        if (!empty($exist)) {
            $exist->content = $data['content'] ?? $exist->content;
            //$exist->save();
            return $exist['target_id'];
        }

        /*$fields = explode(',', $spiderinfo->info_field);
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($spiderinfo['info_db'], $spiderinfo['info_table']);
        $iData = [];
        foreach ($fields as $field) {
            $field = trim($field);
            $iData[$field] = isset($data[$field]) ? $data[$field] : 0;
        }
        $target = $targetModel->addInfo($iData);*/

        $this->create($data);
		//return $target;
        return true;
    }

    public function getFile()
    {
        $spiderinfoCode = $this->spiderinfo['code'];
		$file = "infos/show/{$spiderinfoCode}/{$this->id}-{$this->source_id}.html";
		return $file;
        //$path = ceil($this->source_id / 1000) - 1;
        //file = "enterprise/{$this->source_site_code}/knowledge/{$path}/{$this->source_id}.html";
        if (empty($this->relate_id)) {
            $file = "infos/{$this->source_site}/show/{$this->code}/{$this->source_id}.html";
        } else {
            $file = "infos/{$this->source_site}/show/{$this->relate_id}/{$this->source_id}.html";
        }
    }


    /*public function formatSource($view)
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

	public function _sampleAttachments()
	{
		$code = $this->code_ext;
		$url = "https://tuku.wenes.cn/api.php?op=case&olderId={$code}";
		$r = file_get_contents($url);
		$r = json_decode($r, true);
		if (!isset($r['old'])) {
			return false;
		}
		foreach ($r['old'] as $data) {
            $aData = [
                'info_table' => 'sample',
                'info_id' => $this->target_id,
                'spiderinfo_id' => $this->spiderinfo_id,
                'source_site' => $this->source_site,
                'source_id' => $this->source_id,
                'created_at' => time(),
				'name' => '',
				'filename' => basename($url),
				'description' => '',
				'source_url' => $data['url'],
				'info_field' => 'picture',
            ];

            $this->getPointModel('attachment-bench')->addInfoCheck($aData, ['info_id', 'info_table', 'info_field', 'source_url']);
		}
		$this->status = 100;
		$this->update(false, ['status']);
    }*/
}
