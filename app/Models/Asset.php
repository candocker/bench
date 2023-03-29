<?php

declare(strict_types = 1);

namespace ModuleBench\Models;

class Asset extends AbstractModel
{
	use PageTrait;
    protected $table = 'asset';
    protected $guarded = ['id'];

    public function addElem($data, $pageInfo)
    {
        $info = $this->findone(['url_base' => $data['url_base']]);
        if (empty($info)) {
            $data['code'] = $this->_createCode($data);
            $data['path'] = $this->_createPath($data, $pageInfo);
            //var_dump($data);exit();return;
			$data['name'] = isset($data['name']) ? substr($data['name'], 0, 200) : '';
			return $this->addInfo($data);
        } /*else {
            $info->css_id = $data['css_id'];//$info->code . '<br />';
            echo $info->css_id . '<br />';

            $info->status = 3;
            $info->update(false);
        }*/
    }

    public function spider()
    {
        if ($this->name_ext != 'css') {
            return ;
        }
        $this->urlInfo = parse_url($this->url);

        $path = Yii::$app->params['spiderPath'] . 'pages/source/css/';
        $file = $path . $this->path;
        if (!file_exists($file)) {
            $this->status = 0;
            $this->update(false);
            echo 'nono---' . $file . '<br />';
            return ;
        }

        $pageInfo = Page::findOne($this->page_id);
        $content = file_get_contents($file);
        $assets = $this->getAssets($content);
        foreach ($assets as $asset) {
            $data = $this->formatFile($asset);
            if (empty($data)) {
                continue;
            }
            $data['page_id'] = $this->page_id;
            $data['sort'] = 'ext';
            $data['css_id'] = $this->id;
            $this->addElem($data, $pageInfo);
        }
        $this->status = 2;
        $this->update(false);
    }

    public function deal()
    {
        $this->urlInfo = parse_url($this->url);
        if ($this->name_ext != 'css') {
            return ;
        }

        $path = Yii::$app->params['spiderPath'] . 'pages/source/css/';
        $file = $path . $this->path;
        if (!file_exists($file)) {
            $this->status = 0;
            $this->update(false);
            echo 'nono---' . $file . '<br />';
            return ;
        }
        $fileDeal = str_replace('source/css', '', $file);
        if (file_exists($fileDeal)) {
            echo $fileDeal . '---exists<br />';
            return ;
        }

        $content = file_get_contents($file);
        $assets = $this->getAssets($content);
        $rDatas = [];
        foreach ($assets as $asset) {
            $data = $this->formatFile($asset);
            if (empty($data)) {
                continue;
            }
            $aData = $this->getInfo(['where' => ['url_base' => $data['url_base']]]);
			if (empty($aData['code'])) {
				var_dump($data['url_base']);
			}
            $rDatas[$asset] = $aData['sort'] == 'page' ? '../img/' . $aData['code'] : '../images/' . $aData['code'];
            $this->status = 2;
            //$this->update(false);
        }
        FileHelper::createDirectory(dirname($fileDeal));
        $contentNew = str_replace(array_keys($rDatas), array_values($rDatas), $content);
        file_put_contents($fileDeal, $contentNew);
    }

    public function getFile()
    {
        $path = $this->name_ext == 'css' ? 'pages/source/css/' : 'pages/';
        return $path . $this->path;
	}

	public function down()
	{
		$result = $this->_downFile($this->file, $this->url);
		if (!empty($result)) {
			$this->status = 1;
			if ($result !== true) {
				$this->filesize = intval($result);
			}
		} else {
			$this->status = -1;
		}
		$this->update(false, ['status', 'filesize']);
		return true;
    }

    public function _createCode($data)
    {
        if ($data['name_ext'] != 'js') {
            return substr(md5($data['url']), 0, 5) . '-' . rand(1000, 9999) . '.' . $data['name_ext'];
        }

        return strtolower(str_replace('.js', '', $data['name'])) . '.js';
    }

    public function _createPath($data, $pageInfo)
    {
        $path = $pageInfo['site_code'] . '/';
        $path .= $pageInfo['is_mobile'] == 1 ? 'm/' : '';
        if (in_array($data['name_ext'], ['js', 'css'])) {
            $path .= "{$data['name_ext']}/";
        } else {
            $path .= $data['sort'] == 'ext' ? 'images/' : 'img/';
        }

        $pathMid = $path . $data['code'];
		$exist = $this->getInfo(['where' => ['path' => $pathMid]]);
		if (!empty($exist)) {
			$path = $path . rand(10, 99) . '/' . $data['code'];
		} else {
			$path = $path . $data['code'];
		}
        return $path;
    }

    protected function _getTemplatePointFields()
    {
        return [
            'sort' => ['type' => 'common'],
			'url' => ['type' => 'inline', 'formatView' => 'raw', 'method' => 'formatUrl'],
			'listNo' => [
				'url_base', 'url_remote', 'path'
			],
        ];
    }

	public function formatUrl($view)
	{
		$str = $this->path . '<br />';
		$str .= "<a href='{$this->url}' target='_blank'>{$this->url}</a><br />";
		$str .= "<a href='{$this->url_base}' target='_blank'>{$this->url_base}</a><br />";
		$str .= "<a href='{$this->url_remote}' target='_blank'>{$this->url_remote}</a><br />";
		$localUrl = Yii::$app->params['localUrl'] . 'pages/' . $this->path;
		$str .= "<a href='{$localUrl}' target='_blank'>{$localUrl}</a>";
		return $str;
	}

	public function getStatusInfos()
	{
		return [
			-1 => '异常',
			0 => '未处理',
			1 => '已下载',
			2 => '已二次采集'
		];
	}
}
