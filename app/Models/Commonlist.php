<?php

declare(strict_types = 1);

namespace ModuleBench\Models;

class Commonlist extends AbstractModel
{
    protected $table = 'commonlist';
    protected $guarded = ['id'];

    /*public function getFilterElem($type)
    {
        $filterElems = require(base_path() . '/vendor/candocker/bench/migrations/spiderelems.php');
        $elems = $filterElems[$this->spiderinfo['code']] ?? [];
        if (empty($elems)) {
            return $elems;
        }
        $elems = $elems[$this->code] ?? [];
        if (empty($elems)) {
            return $elems;
        }
        $elems = $elems[$type] ?? [];
        return $elems;
    }*/

    public function getCustomMethod($type)
    {
        $method = '_list' . ucfirst($this->spiderinfo['code']) . ucfirst($this->code) . ucfirst($type);
        return $method;
    }

    public function spiderinfo()
    {
        return $this->hasOne(Spiderinfo::class, 'id', 'spiderinfo_id');
    }

    public function createRecord($spiderinfo, $info)
    {
        $where = ['source_site' => $spiderinfo['site_code'], 'spiderinfo_id' => $spiderinfo['id'], 'source_url' => $info['url']];
        $exist = $this->where($where)->first();
        if (!empty($exist)) {
			if (false) {//isset($info['name'])) {
				$exist->name = $info['name'];
				$exist->code = $info['code'];
				$exist->code_ext = $info['code_ext'];
				//echo $info['name'] . '<br />';
				$r = $exist->save();
				//$r = $exist->update(false, ['name' => $info['name'], 'code' => $info['code']]);
				var_dump($r);
		    }
            return true;
        }

        $data = [
            'spiderinfo_id' => $spiderinfo['id'],
            'source_site' => $spiderinfo['site_code'],
            'source_url' => $info['url'],
            'source_page' => $info['page'],
            'code' => $info['code'],
            'code_ext' => isset($info['code_ext']) ? $info['code_ext'] : '',
            'name' => $info['name'],
        ];
        $this->create($data);
        return true;
    }

    public function getFile()
    {
        $spiderinfoCode = $this->spiderinfo['code'];
		$file = "infos/list/{$spiderinfoCode}/{$this->id}-{$this->source_page}.html";
		return $file;
    }

    /*public function getFile($pointFile = false)
    {
		if ($this->sort != 'single') {
			return '';
		}
        $fileName = $pointFile ?: $this->code;
		return "infos/{$this->site_code}/{$this->sort}/{$fileName}.html";
    }*/
    
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
    }*/
}
