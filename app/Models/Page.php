<?php

declare(strict_types = 1);

namespace ModuleBench\Models;

class Page extends AbstractModel
{
	use PageTrait;
    protected $table = 'page';
    protected $guarded = ['id'];
	public $urlInfo;

    public function spider()
    {
        $this->urlInfo = parse_url($this->url);
        $file = $this->getPointFile($this->_getFile());
        if (!file_exists($file)) {
            $this->status = 0;
            $this->update(false);
            echo $file . '---no<br />';
        }

        $content = file_get_contents($file);
        $assets = $this->getAssets($content);
        foreach ($assets as $asset) {
            $data = $this->formatFile($asset);
            if (empty($data)) {
                continue;
            }
            $data['page_id'] = $this->id;
            $data['sort'] = 'page';
            $this->getPointModel('asset')->addElem($data, $this);
            $this->status = 1;
            $this->update(false);
        }
    }

    public function deal()
    {
        $this->urlInfo = parse_url($this->url);
        $file = $this->getPointFile($this->_getFile());
		echo $file . '<br />';
        if (!file_exists($file)) {
            $this->status = 0;
            $this->update(false);
            echo $file . '---no<br />';
        }
        $fileDeal = str_replace('source', 'html', $file);
        if (file_exists($fileDeal)) {
            echo $fileDeal . '---exists<br />';
            return ;
        }

        $content = file_get_contents($file);
        $assets = $this->getAssets($content);
		$urlPrefix = '<?= Yii::getAlias(\'@tasseturl\'); ?>/';
        $rDatas = [];
        foreach ($assets as $asset) {
            $data = $this->formatFile($asset);
            if (empty($data)) {
                continue;
            }
            $aData = $this->getPointModel('asset')->getInfo(['where' => ['url_base' => $data['url_base']]]);
            $rDatas[$asset] = $urlPrefix . $aData['path'];
            $this->status = 2;
            $this->update(false);
        }
        FileHelper::createDirectory(dirname($fileDeal));
        $contentNew = str_replace(array_keys($rDatas), array_values($rDatas), $content);
		$contentNew = preg_replace("/\n    /", "\n", $contentNew);
		$contentNew = preg_replace("/\n    /", "\n", $contentNew);
		$contentNew = '<?php class Yii { public static function getAlias($var) { return "' . Yii::$app->params['localUrl'] . 'pages' . '"; } } ?>' . "\n" . $contentNew;

        file_put_contents($fileDeal, $contentNew);
    }

    public function getLocalName()
    {
        $name = $this->name . '-' . $this->isMobileInfos[$this->is_mobile];
        return $name;
    }

    public function getUrlLocalName()
    {
        $name = $this->getLocalName();
        $url = $this->_getFileUrl();
        $file = $this->_getFile();
        if (file_exists($file)) {
            return "<a href='{$url}' target='_blank'>{$name}</a>";
        }
        return $name;
    }

    protected function _getTemplatePointFields()
    {
        return [
            'sort' => ['type' => 'common'],
			'url' => ['type' => 'inline', 'formatView' => 'raw', 'method' => 'getFormatUrl'],
			'extFields' => ['operation'],
			'status' => ['type' => 'changedown'],
			'listNo' => [
			],
        ];
    }

	public function getFormatUrl($view)
	{
		$str = "<a href='{$this->url}' target='_blank'>源URL</a>---";
		if ($this->fileExist($this->_getFile())) {
			$localUrl = $this->_getFileUrl();
			$str .= "<a href='{$localUrl}' target='_blank'>本地初始URL</a>---";
		} else {
			$str .= '本地源文件不存在---';
		}

		if ($this->fileExist($this->_getFile(true))) {
			$localUrl = $this->_getFileUrl(true);
			$str .= "<a href='{$localUrl}' target='_blank'>本地URL</a>";
		} else {
			$str .= '本地文件不存在';
		}
		return $str;
	}

    public function formatOperation($view)
    {
        $menuCodes = [
			'bench-spider_asset_listinfo' => '',
			'bench-spider_page_operation' => ['name' => '页面采集', 'qStr' => 'action=spider'],
			'bench-spider_asset_operation' => ['name' => '二次采集', 'qStr' => 'action=spider'],
		];
        $str = $this->_formatMenuOperation($view, $menuCodes, ['page_id' => 'id'], ['target' => '_blank']) . '<br />';
        $menuCodes = [
			'bench-spider_page_operation' => ['name' => '页面本地化', 'qStr' => 'action=deal'],
			'bench-spider_asset_operation' => ['name' => '二次本地化', 'qStr' => 'action=deal'],
			'bench-spider_asset_down' => '',
		];
        $str .= $this->_formatMenuOperation($view, $menuCodes, ['page_id' => 'id'], ['target' => '_blank']);
		return $str;
    }

    protected function _getFile($deal = false)
    {
        $isMobile = $this->is_mobile == 1 ? '-m' : '';
		$path = $deal ? 'pages/html' : 'pages/source';
        return "{$path}/{$this->site_code}{$isMobile}/{$this->code}.php";
    }
}
