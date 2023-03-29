<?php

namespace bench\spider\models;

use Yii;
use Symfony\Component\DomCrawler\Crawler;
use bench\models\BaseModel as BaseModelBase;

class BaseModel extends BaseModelBase
{
    public function fileExist($file)
    {
        $file = $this->getPointFile($file);
        return file_exists($file);
    }

    public function getLocalUrl($file)
    {
        return Yii::$app->params['localUrl'] . $file;
    }

    public function getPointFile($file)
    {
        return Yii::$app->params['spiderPath'] . $file;
    }

    public function getContent($file)
    {
        $file = $this->getPointFile($file);
        if (!file_exists($file)) {
            echo $file . '<br />';
            $this->status = 99;
            $this->save();
            return false;
        }
        $content = file_get_contents($file);
        //$content = str_replace('text/html; charset=gb2312', 'text/html; charset=utf8', $content);
        //echo $content;exit();
        return $content;
    }

    public function getCrawlerFile($file)
    {
        $crawler = new Crawler();
        $crawler->addContent($this->getContent($file));
        return $crawler;
    }

    protected function _getFileUrl($deal = false)
    {
        $url = Yii::$app->params['localUrl'];
        $file = $this->_getFile($deal);

        return $url . $file;
    }

    public function getSiteCodeInfos()
    {
        return [
            'jmnine' => '91加盟',
            'kenter' => '快法务',
            'groupon5' => '家博会',
            'office' => '公装',
            'newspread' => '家装推广',
            'selectright' => '选对的',
            'maigoo' => '买购',
            'trademark' => '品牌',
            'culture' => '文化',
            'petinfo' => '宠物',
        ];
    }

    public function getAttachmentMark()
    {
        return 'spider';
    }

    public function getCrawlerElem($node, $dom, $mark, $method = 'attr')
    {
        $elem = $node->filter($dom);

        if (count($elem) <= 0) {
            return '';
        }
        switch ($method) {
        case 'attr':
            return trim($elem->$method($mark));
        case 'text':
            return trim($elem->text());
        }
    }

    public function getSourceId($string, $replace = '.html')
    {
        $sourceId = basename($string);
        return str_replace($replace, '', $sourceId);
    }

    public function getCrawlerTag($node, $dom, $skip = '全选')
    {
        $tags = $node->filter($dom);
        $tagStr = '';
        foreach ($tags as $tag) {
            $value = trim($tag->nodeValue);
            $tagStr .= $value == $skip ? '' : $value . ',';
        }
        return $tagStr;
    }
}
