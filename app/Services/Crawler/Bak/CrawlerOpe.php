<?php

declare(strict_types = 1);

namespace ModuleBench\Services\Crawler;
        
trait CrawlerOpe
{
	protected function dealContent($crawler, $info, $content)
	{
        $pictures = [];
        $pattern = '@<p>.*<img.*src=.*"(?P<img>.*)".*>@Us';
        preg_match_all($pattern, $content, $infos);
        $pictures = $infos['img'];
        if (empty($pictures)) {
            $pattern = '@{"type":"image","content":"(?P<img>.*)"}@Us';
            preg_match_all($pattern, $content, $infos);
            $pictures = $infos['img'];
        }
		$pictures = array_filter($pictures);
        if (empty($pictures)) {
            echo $info['source_id'] . 'nopicture<br />';
        }
		echo $info['id'] . '--p--' . count($pictures) . '<br />';
        $this->_addPicture($pictures, 'picture', $info);
		return count($pictures);
    }

    protected function _dealContent($content, $aDatas)
    {
        if (empty($content)) {
            return '';
        }
        $replaces = [];
        foreach ($aDatas as $aData) {
            $sourceUrl = $aData['source_url_full'] ?? $aData['source_url'];
			$attachment = $this->_getAttachmentData($aData);
            $replaces[$sourceUrl] = $attachment->_getFile();
        }
		//print_r($replaces);
        $content = str_replace(array_keys($replaces), array_values($replaces), $content);
		return $content;
    }

    protected function _getAttachmentData($aData)
    {
		/*$baseField = ['name', 'source_id', 'source_url', 'info_table', 'info_field', 'info_id'];
		foreach ($baseFields as $bField) {
			if (isset($aData[$bField])) {
				return null;
			}
		}*/
        $info = array_merge($aData, [
            'spiderinfo_id' => $this->id,
            'source_site' => $this->site_code,
            'filename' => $aData['name'],
            'description' => $aData['name'],
            'created_at' => time(),
        ]);
        $attachment = $this->getPointModel('attachment-bench')->addInfoCheck($info, ['info_id', 'info_table', 'info_field', 'source_url']);
		return $attachment;
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

    public function crawlerOperations()
    {
        $elems = $crawler->filter('.odds_down li img');
        for ($i = 0; $i < count($elems); $i++) {
            $sourceUrl = $elems->eq($i)->attr('data-original');
        }
        $crawler->filter('.swiper-slide')->each(function ($node) use (& $datas, $commonlist) {
            $baseElem = $node->filter('a');
            $sourceUrl = $baseElem->attr('href');
		});

        $crawler = new Crawler();
        $crawler->addContent($this->getContent($file));

        $crawler->filter('.wrap .merger_box')->each(function ($node) use (& $i, & $data) {
			$nameBase = $node->filter('h2 a');
			if (count($nameBase) < 1) {
				return ;
			}

			$cName = $node->text();
            $name = $node->filter('div h3')->text();
            $pCode = Pinyin::letter($cName, ['delimiter' => '', 'accent' => false]);
            $pCode = Pinyin::trans($cName, ['delimiter' => '', 'accent' => false]);

		    $thumb = $node->filter('img');
		    $thumb = count($thumb) > 0 ? $thumb->attr('src') : '';

            $created_at = $attrs->eq(0)->text();//nodeValue;
            $created_at = strtotime($created_at);

            $author = $attrs->eq(1)->text();//nodeValue;
            $content = trim($crawler->filter('#ctrlfscont')->html());
            $content = preg_replace("'<script(.*?)<\/script>'is", '', $content);
            //echo $content;exit();
        });


        $tags = $node->filter('a');
        foreach ($tags as $key => $attr) {
            $value = trim($attr->nodeValue);
            $data[$name][$subName][] = $value;
        }
        $tagInfos = $node->filter('.tags')->eq(0)->filter('em');
    }

	public function _listDealDemo($crawler, $commonlist)
	{
		$datas = [];
		$i = 1;
        $crawler->filter('.theory-list-left-con .theory-list-item')->each(function ($node) use (& $datas, $commonlist, & $i) {
            $imgObj = $node->filter('.theory-list-img-div img');
			$num = count($imgObj);
			if ($num < 1) {
				return ;
			}
			//echo count($imgObj);exit();
            $img = $imgObj->attr('src');

			$baseElem = $node->filter('.theory-list-info');
            $sourceUrl = $node->filter('a')->first()->attr('href');
            $name = $baseElem->filter('.theory-list-name')->text();
            $title = $baseElem->filter('.theory-list-depict')->text();

			$tags = $node->filter('.theory-list-type a');
			$tagStr = '';
			foreach ($tags as $key => $attr) {
				$tagStr .= trim($attr->nodeValue) . ',';
			}
			//echo $tagStr . '==' . $title . '==' . $name . '==' . $sourceUrl . '==' . $img;exit();

            $sourceId = str_replace('.html', '', basename($sourceUrl));
			$datas[] = [
				'source_url' => $sourceUrl,
				'name' => $name,
				'code' => $sourceId,
				'content' => '',
				'tag' => $tagStr,
				'description' => $title,
				'source_id' => $sourceId,
				'thumb' => $img,
			];
		});
		//print_r($datas);exit();
		return $datas;
	}

	public function _infoDealDemo($crawler, $commoninfo)
	{
        $targetInfo = $this->spiderinfo->getTargetInfo();
		$description = $crawler->filter('.news-main-dis')->html();
		$content = $crawler->filter('.news-main-info')->html();
        $aDatas = []; 
		$crawler->filter('.news-main-info img')->each(function ($subNode) use (& $aDatas, $commoninfo, $info) {
			$aData = [
                'source_id' => $commoninfo['source_id'],
				'source_url' => trim($subNode->attr('src')),
                'info_table' => 'info',
                'info_field' => 'picture',
                'info_id' => $info['id'],
				'name' => $info['name'],
			];
			$aDatas[] = $aData;
		});
		$info->description = trim($description);
		$content = $this->_dealContent($content, $aDatas);
		$info->content = $content;
		$info->update(false, ['description', 'content']);
		return true;
	}

    public function _createSqlDemo($crawler)
    {
        $str = '';
		$sql = 'INSERT INTO `wp_space` (`name`, `code`, `description`, `parent_code`) VALUES ';
		$i = 1;
		$crawler->filter('.home-side-main li')->each(function ($node) use (& $sql) {
			$iName = $node->filter('a');
			$class = $iName->attr('class');
			if ($class == 'home-side-sort1') {
			$name = count($iName) > 0 ? trim($iName->text()) : '';
			$description = $iName->attr('href');
            $code = Pinyin::letter($name, ['delimiter' => '', 'accent' => false]);
			$sql .= "('{$name}', '{$code}', '{$description}', ''),<br />\n";
			} else {
				return ;
			}
			//echo $name . '-' . $description . '-' . $code;exit();
			$node->filter('.home-side-tab-item li')->each(function ($subNode) use (& $sql, $code) {
			    $tmp = $subNode->filter('a');
			    $subName = count($tmp) > 0 ? trim($tmp->text()) : '';
			    $subDescription = $tmp->attr('href');
                $subCode = Pinyin::letter($subName, ['delimiter' => '', 'accent' => false]);

			    $sql .= "('{$subName}', '{$subCode}', '{$subDescription}', '{$code}'),<br />\n";

			});
				//echo $sql;exit();
		});
		echo $sql;exit();
    }
}
