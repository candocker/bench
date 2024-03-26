<?php

declare(strict_types = 1);

namespace ModuleBench\Models;

class Page extends AbstractModel
{
    //use PageTrait;
    protected $table = 'page';
    protected $guarded = ['id'];
    public $timestamps = false;
    //public $urlInfo;

    public function getPageFile($deal = false)
    {
        $isMobile = $this->is_mobile == 1 ? '-m' : '';
        $path = $deal ? 'pages/html' : 'pages/source';
        return "{$path}/{$this->site_code}{$isMobile}/{$this->code}.php";
    }

    /*public function getLocalName()
    {
        $name = $this->name . '-' . $this->isMobileInfos[$this->is_mobile];
        return $name;
    }*/

    /*public function getUrlLocalName()
    {
        $name = $this->getLocalName();
        $url = $this->_getFileUrl();
        $file = $this->_getFile();
        if (file_exists($file)) {
            return "<a href='{$url}' target='_blank'>{$name}</a>";
        }
        return $name;
    }*/

    /*protected function _getTemplatePointFields()
    {
        return [
            'sort' => ['type' => 'common'],
            'url' => ['type' => 'inline', 'formatView' => 'raw', 'method' => 'getFormatUrl'],
            'extFields' => ['operation'],
            'status' => ['type' => 'changedown'],
            'listNo' => [
            ],
        ];
    }*/

    /*public function getFormatUrl($view)
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
    }*/

    /*public function formatOperation($view)
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
    }*/
}
