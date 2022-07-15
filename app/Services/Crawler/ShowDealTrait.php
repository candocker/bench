<?php

declare(strict_types = 1);

namespace ModuleBench\Services\Crawler;

use yii\helpers\FileHelper;

trait ShowDealTrait
{
    protected function _infoPetinfoPet_pet($crawler, $commoninfo)
    {
        //print_r($commoninfo->toArray());exit();
        $spiderinfo = $commoninfo->spiderinfoData;
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, $spiderinfo['info_table']);
        $info = $targetModel->getInfo($commoninfo->target_id);

		$content = $crawler->filter('.entry_content')->html();
        $aDatas = []; 
		$crawler->filter('.entry_content img')->each(function ($subNode) use (& $aDatas, $commoninfo, $info, $spiderinfo) {
            $sUrl = trim($subNode->attr('src'));
            $name = trim($subNode->attr('title'));
            $name = empty($name) ? $info['name'] : $name;
			$aData = [
                'source_id' => $commoninfo['source_id'],
				'source_url' => $sUrl,
                'info_table' => $spiderinfo['info_table'],
                'info_field' => 'content',
                'info_id' => $info['id'],
				'name' => $name,
			];
			$aDatas[] = $aData;
		});
        //print_R($aDatas);
		$content = $this->_dealContent($content, $aDatas);
		$info->content = $content;

		$info->update(false, ['content']);
		return true;
    }

    protected function _infoCultureCulture_article($crawler, $commoninfo)
    {
        //print_r($commoninfo->toArray());exit();
        $spiderinfo = $commoninfo->spiderinfoData;
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, $spiderinfo['info_table']);
        $info = $targetModel->getInfo($commoninfo->target_id);
		$description = $crawler->filter('.note')->html();
		$content = $crawler->filter('#newsContent')->html();
        $aDatas = []; 
		$crawler->filter('#newsContent img')->each(function ($subNode) use (& $aDatas, $commoninfo, $info, $spiderinfo) {
            $sUrl = trim($subNode->attr('src'));
            $name = trim($subNode->attr('title'));
            $name = empty($name) ? $info['name'] : $name;
			$aData = [
                'source_id' => $commoninfo['source_id'],
				'source_url' => $sUrl,
                'info_table' => $spiderinfo['info_table'],
                'info_field' => 'content',
                'info_id' => $info['id'],
				'name' => $name,
			];
			$aDatas[] = $aData;
		});
		$info->description = trim($description);
        $publishAt = $crawler->filter('.addi2')->text();
        $publishAt = str_replace(' ', '', $publishAt);
        $publishAt = strpos($publishAt, '作者') !== false ? substr($publishAt, 0, strpos($publishAt, '作者')) : $publishAt;
        $publishAt = trim($publishAt);
        $publishAt = str_replace(['时间：'], [''], $publishAt);
        //print_R($aDatas);exit();
		$content = $this->_dealContent($content, $aDatas);
		$info->content = $content;

		$info->update(false, ['content', 'publish_at']);
		return true;
    }

    protected function _infoPetinfoPet_article($crawler, $commoninfo)
    {
        //print_r($commoninfo->toArray());
        $spiderinfo = $commoninfo->spiderinfoData;
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, $spiderinfo['info_table']);
        $info = $targetModel->getInfo($commoninfo->target_id);
        //print_R($info);exit();
		$description = $crawler->filter('.abstract_center')->html();
		$content = $crawler->filter('.article_body')->html();
        $aDatas = []; 
		$crawler->filter('.article_body img')->each(function ($subNode) use (& $aDatas, $commoninfo, $info, $spiderinfo) {
            $sUrlFull = trim($subNode->attr('src'));
            $sUrl = $sUrlFull;//str_replace('_y.png', '.png', $sUrlFull);
			$aData = [
                'source_id' => $commoninfo['source_id'],
				'source_url' => $sUrl,
                //'source_url_full' => $sUrlFull,
                'info_table' => $spiderinfo['info_table'],
                'info_field' => 'content',
                'info_id' => $info['id'],
				'name' => $info['name'],
			];
			$aDatas[] = $aData;
		});
		$info->description = trim($description);
        $info->publish_at = $crawler->filter('.article_title span')->text();
		$content = $this->_dealContent($content, $aDatas);
        $content = str_replace(['<div class="article_copyright">波奇网诚征优秀稿件：<a href="http://www.boqii.com/copyright.html" target="_blank">投稿指南</a></div>'], [''], $content);
		$info->content = $content;

        $pictureUrl = $crawler->filter('.article_top_img img');
        $pictureUrl = count($pictureUrl) >= 1 ? $pictureUrl->attr('src') : false;
        //print_r($aDatas);
        //echo $pictureUrl . '==' . $description . '--' . $content;
        if (!empty($pictureUrl)) {
            $pData = [
                'picture' => $pictureUrl,
                'name' => $info['name'],
                'source_id' => $commoninfo['source_id'],
            ];
            $r = $this->getPointModel('attachment-bench')->createRecord($pData, $spiderinfo, $info['id']);
        }
		$info->update(false, ['description', 'content', 'publish_at']);
		return true;
    }

    protected function _infoXstxtXstxt($crawler, $commoninfo)
    {
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, 'chapter');
        $chapter = $targetModel->getInfo($commoninfo->target_id);

        /*$content = '';
        $elems = $crawler->filter('.zw p');
        for ($i = 0; $i < count($elems); $i++) {
            $value = $elems->eq($i)->text();
            $content .= $value . "\r\n\r\n";
        }*/

        $content = trim($crawler->filter('.zw')->html());
        //echo $content;exit();
        $content = str_replace('<br>', "\r\n", $content);
        //echo $content;exit();
        $file = $this->getPointModel('chapter')->getInfo($chapter->id)->getChapterFile($chapter, false);
        //echo $file;exit();
        FileHelper::createDirectory(dirname($file), 0755);
        file_put_contents($file, $content);
        return false;
        //return true;
    }
}
