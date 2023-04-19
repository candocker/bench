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
}
