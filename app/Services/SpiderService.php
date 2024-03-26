<?php
declare(strict_types = 1);

namespace ModuleBench\Services;

use Symfony\Component\DomCrawler\Crawler;
use Swoolecan\Foundation\Helpers\DatetimeTool;

class SpiderService extends AbstractService
{
    use \ModuleBench\Services\Crawler\CrawlerTrait;

    public $spiderinfo;

    public function spider($info, $type = '')
    {
        $result = $this->_downFile($info->getFile(), $info->source_url);
        $info->status = !empty($result) ? 1 : 99;
        $info->save();
        return true;
    }

    public function getContent($file)
    {
        $file = $this->getPointFile($file);
        if (!file_exists($file)) {
            //echo $file . '<br />';
            return false;
        }
        $content = file_get_contents($file);
        //$content = str_replace('text/html; charset=gb2312', 'text/html; charset=utf8', $content);
        //echo $content;exit();
        return $content;
    }

    public function getCrawlerObj($file)
    {
        $crawler = new Crawler();
        $content = $this->getContent($file);
        if (empty($content)) {
            return false;
        }
        //$content = str_replace(['<br />', '<br>'], ['</div><div>', '</div><div>'], $content);
        //echo $content;exit();
        $crawler->addContent($content);
        return $crawler;
    }

    /*public function getAttachmentMark()
    {
        return 'spider';
    }

    public function getSiteCodeInfos()
    {
        return [
            'culture' => '文化',
            'five' => '五千言',
        ];
    }

    public function getLocalUrl($file)
    {
        return Yii::$app->params['localUrl'] . $file;
    }

    protected function _getFileUrl($deal = false)
    {
        $url = Yii::$app->params['localUrl'];
        $file = $this->_getFile($deal);

        return $url . $file;
    }

    public function fileExist($file)
    {
        $file = $this->getPointFile($file);
        return file_exists($file);
    }*/
}
