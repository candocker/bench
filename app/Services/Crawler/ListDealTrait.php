<?php

declare(strict_types = 1);

namespace ModuleBench\Services\Crawler;

trait ListDealTrait
{
	protected function _listGushiwenGushiwen($crawler, $commonlist)
    {
		$datas = [];
        $crawler->filter('.item-shici')->each(function ($node) use (& $datas, $commonlist) {
            $baseObj = $node->filter('h3 a');
            $sourceUrl = $baseObj->attr('href');

            $sourceId = basename($sourceUrl);
			$sourceId = str_replace('.html', '', $sourceId);
            $name = $baseObj->text();
			$datas[] = [
				'source_url' => 'https://www.gushiju.net' . $sourceUrl,
				'name' => $name,
				'sort' => $commonlist['code_ext'],
				'code' => $sourceId,
				'name' => $name,
				'title' => $name,
                'code_ext' => $name,
				'source_id' => $sourceId,
				'content' => '',
			];
            //print_r($datas);exit();
		});
		//print_R($datas);exit();
		return $datas;
    }

	//protected function _listGuoxueGuoxue($crawler, $commonlist)
	//protected function _listGxbaodianGxbaodian($crawler, $commonlist)
	protected function _list5000yanFive($crawler, $commonlist)
    {
		$datas = [];
        $i = 1;
        $selectStr = '.gxllist4 li'; // guoxue
        $selectStr = '.lunyu_section li'; // gxbaodian
        $selectStr = '.section-body ul li'; // file 1
        $selectStr = '.main-content ul li'; // file 2
        $selectStr = '.main-content-shouye p';
        $selectStr = '.main-content .qianziwen a';
        $crawler->filter($selectStr)->each(function ($node) use (& $datas, $commonlist, & $i) {
            $baseObj = $node->filter('a');
            $sourceUrl = $baseObj->attr('href');

            $sourceId = basename($sourceUrl);
			$sourceId = str_replace('.html', '', $sourceId);
            $name = $baseObj->text();
            $name = str_replace(['大戴礼记·', '尔雅·'], ['', ''], $name);
			$datas[] = [
				'source_url' => $sourceUrl,
				'name' => $name,
				'code' => $i,
				'name' => $name,
                'relate_id' => $commonlist['id'],
                //'code_ext' => $name,
				'source_id' => $sourceId,
			];
            $i++;
		});

        //$selectStr = '.main-content article'; // file 2
        $selectStr = '.main-content-shouye div'; // file 2
        $crawler->filter($selectStr)->each(function ($node) use (& $datas, $commonlist, & $i) {
            //$chapter = trim($node->filter('h2')->text());
            $chapter = '';//trim($node->filter('.menu-item-object-category p')->text());
            $description = '';//trim($node->filterXPath('//div[contains(@class,"shi-jianju")]')->text());

            $node->filter('a')->each(function ($subNode) use ($chapter, $description, & $datas, $commonlist, & $i) {
                $sourceUrl = $subNode->attr('href');
    
                $sourceId = basename($sourceUrl);
    			$sourceId = str_replace('.html', '', $sourceId);
                $name = $subNode->text();
                $name = str_replace(['大戴礼记·', '尔雅·'], ['', ''], $name);
    			$datas[] = [
    				'source_url' => $sourceUrl,
    				'name' => $name,
    				'code' => $i,
    				'name' => $name,
                    'relate_id' => $commonlist['id'],
                    'extfield' => $chapter,
                    'title' => $description,
    				'source_id' => $sourceId,
    			];
                $i++;
		    });
		});

        print_r($datas);exit();
		return $datas;
    }

	protected function _listPetinfoPet_pet($crawler, $commonlist)
	{
		$datas = [];
        $crawler->filter('.hot_pet_cont dl')->each(function ($node) use (& $datas, $commonlist) {
            $baseObj = $node->filter('dt a');
			$num = count($baseObj);
			if ($num < 1) {
				//return ;
				$baseObj = $node->filter('dd a');
				$img = '';
				$name = $baseObj->text();
			} else {
    			//echo count($baseObj);return ;exit();
                $img = $baseObj->filter('img')->attr('src');
                $name = $baseObj->filter('img')->attr('alt');
			}
            $sourceUrl = $baseObj->attr('href');
			//echo $sourceUrl . '==' . '==' . $img . '--' . $name . '==<br />';

            $sourceId = basename($sourceUrl);
			$sourceId = str_replace('.html', '', $sourceId);
			$datas[] = [
				'source_url' => $sourceUrl,
				'name' => $name,
				'sort' => $commonlist['code_ext'],
				'code' => $sourceId,
				'name' => $name,
				'title' => $name,
				'source_id' => $sourceId,
				'content' => '',
				'thumb' => $img,
			];
		//print_R($datas);exit();
		});
		return $datas;
	}

	protected function _listPetinfoPet_article($crawler, $commonlist)
	{
		$datas = [];
        $crawler->filter('.art_list dl')->each(function ($node) use (& $datas, $commonlist) {
            $baseObj = $node->filter('dt a');
			$num = count($baseObj);
			if ($num < 1) {
				$baseObj = $node->filter('.name a');
				$img = '';
				$name = $baseObj->text();
			} else {
				return ;
    			//echo count($baseObj);return ;exit();
                $img = $baseObj->filter('img')->attr('src');
    			//$img = 'http://www.yac8.com' . ltrim($img, '.');
                $name = $baseObj->filter('img')->attr('alt');
			}
            $sourceUrl = $baseObj->attr('href');
    		//$sourceUrl = 'http://www.yac8.com' . ltrim($sourceUrl, '.');
			$description = $node->filter('.cont')->text();
			//echo $sourceUrl . '==' . '==' . $img . '--' . $name . '==<br />';

            $sourceId = basename($sourceUrl);
			$sourceId = str_replace('.html', '', $sourceId);
			$datas[] = [
				'source_url' => $sourceUrl,
				'name' => $name,
				'category_code' => $commonlist['code_ext'],
				'code' => $sourceId,
				'name' => $name,
				'title' => $name,
				'source_id' => $sourceId,
				'content' => '',
				'thumb' => $img,
			];
		print_R($datas);exit();
		});
		return $datas;
	}

	protected function _listCultureCulture_article($crawler, $commonlist)
	{
		$datas = [];
        $crawler->filter('.listBox2 li')->each(function ($node) use (& $datas, $commonlist) {
            $baseObj = $node->filter('.img a');
			$num = count($baseObj);
			if ($num < 1) {
                $baseObj = $node->filter('.b a');
    			$img = '';
                $name = $baseObj->text();
			} else {
				return ;
    			//echo count($imgObj);return ;exit();
                $img = $baseObj->filter('img')->attr('src');
    			$img = 'http://www.yac8.com' . ltrim($img, '.');
                $name = $baseObj->filter('img')->attr('alt');
			}
            $sourceUrl = $baseObj->attr('href');
    		$sourceUrl = 'http://www.yac8.com' . ltrim($sourceUrl, '.');

			$description = $node->filter('.note')->text();
			$tag = '';
            $node->filter('.mark a')->each(function ($subNode) use (& $datas, $commonlist, & $tag) {
				$mark = $subNode->text();
				$tag .= $mark . ',';
		    });
			$tag = trim(trim($tag, ''), ',');
			//echo $sourceUrl . '==' . '==' . $img . '--' . $name . '==' . $tag . '<br />';

            $sourceId = basename($sourceUrl);
			$datas[] = [
				'source_url' => $sourceUrl,
				'name' => $name,
				'code' => $sourceId,
				'category_code' => $commonlist['code'],
				'name' => $name,
				'description' => $description,
				'tag' => $tag,
				'title' => $name,
				'source_id' => $sourceId,
				'thumb' => $img,
			];
		//print_R($datas);exit();
		});
		return $datas;
	}

	protected function _listXstxtXstxt($crawler, $commonlist)
	{
		$datas = [];
		$i = 1;
        $crawler->filter('#yuedu table ul')->each(function ($middle) use (& $datas, $commonlist, & $i) {
			$j = 0;
            $middle->filter('li')->each(function ($node) use (& $datas, $commonlist, $i, & $j) {
                $source = $node->filter('a');
    			if (count($source) < 1) {
    				return ;
    			}
    			$sourceUrl = 'https://www.xstt5.com' . trim($source->attr('href'));
                $name = trim($node->filter('a')->text());
    
                $sourceId = str_replace('.html', '', basename($sourceUrl));
    			$datas[] = [
					'serial' => $j * 3 + $i,
    				'source_url' => $sourceUrl,
    				'name' => $name,
					'title' => '',
					'author' => '',
					'book_code' => $commonlist['code'],
					'code' => $j * 3 + $i,
    				'brief' => $name,
    				'source_id' => $sourceId,
    			];
				$j++;
		    });
			$i++;
		});
		//print_r($datas);exit();
		return $datas;
	}

	protected function _listTg51Website($crawler, $commonlist)
	{
		$datas = [];
        $crawler->filter('.shop_content_list li')->each(function ($node) use (& $datas, $commonlist) {
            $imgObj = $node->filter('.shop_content_list_up .shop_logo');
			$num = count($imgObj);
			if ($num < 1) {
				return ;
			}
			//echo count($imgObj);exit();
            $img = $imgObj->attr('src');
			$baseElem = $node->filter('.shop_content_list_middle');
            $sourceUrl = $baseElem->filter('a')->attr('href');
            $name = $baseElem->filter('a')->text();
            $title = $baseElem->filter('p')->text();

            $sourceId = basename($sourceUrl);
			$datas[] = [
				'source_url' => $sourceUrl,
				'name' => $name,
				'code' => $sourceId,
				'name' => $name,
				'title' => $title,
				'source_id' => $sourceId,
				'logo' => $img,
			];
		});
        $crawler->filter('.brand_content_list li')->each(function ($node) use (& $datas, $commonlist) {
			$imgObj = $node->filter('.brand_content_list_up a img');
			$num = count($imgObj);
			if ($num < 1) {
				return ;
			}
			$img = $imgObj->attr('data-original');
            $sourceUrl = $node->filter('.brand_content_list_middle a')->attr('href');
            $name = $node->filter('.brand_content_list_middle a')->text();
            $title = $node->filter('.brand_content_list_middle p')->text();

            $sourceId = basename($sourceUrl);
			$datas[] = [
				'source_url' => $sourceUrl,
				'name' => $name,
				'code' => $sourceId,
				'brief' => $title,
				'source_id' => $sourceId,
				'logo' => $img,
			];
		});
		//print_R($datas);exit();
		return $datas;
	}
}
