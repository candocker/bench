<?php

declare(strict_types = 1);

namespace ModuleBench\Services\Crawler;

trait ListDealTrait
{
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
}
