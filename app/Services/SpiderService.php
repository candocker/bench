<?php
declare(strict_types = 1);

namespace ModuleBench\Services;

use Symfony\Component\DomCrawler\Crawler;
use Swoolecan\Foundation\Helpers\DatetimeTool;

class SpiderService extends AbstractService
{
    use \ModuleBench\Services\Crawler\CrawlerTrait;

    public $spiderinfo;

    public function spiderinfoOperation($action, $params)
    {
        $action = $this->resource->strOperation($action, 'camel');
        if ($action == 'local') {
            $file = $this->spiderinfo->getFile($params['point_file'] ?? false);
            return ['type' => 'newPage', 'url' => $this->getConfig('uploadUrl', 'domains') . $file];
        }

        if ($this->spiderinfo->status == 100) {
            return ['type' => 'common', 'message' => '操作已锁定'];
        }

        return $this->spiderinfoDeal($action, $params);
    }

    public function spiderinfoDeal($type, $params)
    {
        $siteCode = $this->resource->strOperation($this->spiderinfo->site_code, 'studly');
        $code =  $this->resource->strOperation($this->spiderinfo->code, 'studly');
        $crawlerMethod = "_{$type}{$siteCode}{$code}";
        \Log::debug('spider-method-' . $crawlerMethod);
        if ($type == 'record') {
            $check = $this->params['check'] ?? false;
            return $this->$crawlerMethod($check);
        }

        $pointInfo = $type == 'single' ? $this->spiderinfo : $params['info'];
        $crawlerObj = $this->getCrawlerObj($pointInfo->getFile());
        if (empty($crawlerObj) && $type == 'single') {
            $pointInfo->status = 99;
            $pointInfo->save();
            return false;
        }

        return $this->$crawlerMethod($crawlerObj, $pointInfo);
    }

    public function spider($info, $type = '')
    {
        $result = $this->_downFile($info->getFile(), $info->source_url);
        $info->status = !empty($result) ? 1 : 99;
        $info->save();
        return true;
    }

    public function deal($info, $type)
    {
        $result = $this->spiderinfoDeal($type, ['info' => $info]);
        if (empty($result)) {
			$info->status = 99;
            $info->save();
            return false;
        }

        if ($type == 'info') {
            $info->status = $result === true ? 2 : 98;
		    $info->save();
		    return true;
        }

        $spiderNum = $spiderSourcenum = 0;
        foreach ($result as $data) {
            $target = $this->getModelObj('commoninfo')->createRecord($data, $this->spiderinfo, $info);
			$spiderSourcenum += 1;
            if (is_object($target)) {
                $spiderNum += 1;
            }

            //$targetId = is_object($target) ? $target['id'] : $target;
            //$this->getPointModel('attachment-bench')->createRecord($data, $this, $targetId);
        }

    	$info->spider_num = $info->spider_num + $spiderNum;
    	$info->spider_sourcenum = $info->spider_num + $spiderSourcenum;
    	$info->status = 2;
    	$info->save();
        return true;
    }

    public function fileExist($file)
    {
        $file = $this->getPointFile($file);
        return file_exists($file);
    }

    public function getConfig($code, $path = 'bench')
    {
        $param = $path ? "app.{$path}.{$code}" : "app.{$code}";
        return config($param);
    }

    public function getPointFile($file)
    {
        return $this->getConfig('spiderPath', 'bench') . $file;
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

    public function getAttachmentMark()
    {
        return 'spider';
    }
}
