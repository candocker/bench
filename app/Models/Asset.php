<?php

declare(strict_types = 1);

namespace ModuleBench\Models;

class Asset extends AbstractModel
{
    //use PageTrait;
    protected $table = 'asset';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function getAssetFile()
    {
        $path = $this->name_ext == 'css' ? 'pages/source/css/' : 'pages/';
        return $path . $this->path;
    }

    public function pageInfo()
    {
        return $this->hasOne(Page::class, 'id', 'page_id');
    }

    /*protected function _getTemplatePointFields()
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
    }*/
}
