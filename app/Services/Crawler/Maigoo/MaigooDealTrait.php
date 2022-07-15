<?php

declare(strict_types = 1);

namespace ModuleBench\Services\Crawler\Maigoo;

use Overtrue\Pinyin\Pinyin;
// INSERT INTO `wp_product_sale` (`name`, `category_code`, `brand_code`, `product_id`, `direct_url`, `price`, `price_market`, `description`) 
// SELECT `name`, `category_code`, `brand_code`, `id`, `extfield`, `price`, `price`, `extfield2` FROM `wp_product` WHERE 1;
// UPDATE `wp_product_sale` AS `ps`, `wp_brand_store` AS `bs` SET `ps`.`store_code` = `bs`.`code` WHERE `ps`.`description` = `bs`.`name` AND 1;
// UPDATE `wp_product_sale` SET `buyer_id` = 1 WHERE 1;

Trait MaigooDealTrait
{
    protected function _listMaigooHuman($crawler, $commonlist)
    {
        //print_r($commonlist->toArray());exit();
        $aDatas = [];
        $crawler->filter('.itembox .item')->each(function ($subNode) use (& $aDatas, $commonlist) {
            $sourceUrl = $this->getCrawlerElem($subNode, '.contbox a', 'href', 'attr');
            if (empty($sourceUrl)) {
                return ;
            }
			$sourceId = $this->getSourceId($sourceUrl);
            $name = $this->getCrawlerElem($subNode, '.contbox a', '', 'text');
            $thumb = $this->getCrawlerElem($subNode, '.img a img', 'src', 'attr');
            $thumb = str_replace('_220_135.jpg', '', $thumb);
            $description = $this->getCrawlerElem($subNode, '.description', '', 'text');
            $tagStr = $this->getCrawlerTag($subNode, '.other a');

            $aDatas[] = [
                'source_url' => $sourceUrl,
                'name' => $name,
                'code' => $sourceId,
                'code_ext' => $commonlist['code_ext'],
                'brand_code' => $commonlist['code_ext'],
                'description' => $description,
                'tag' => $tagStr,
                'source_id' => $sourceId,
                'content' => '',
                'thumb' => $thumb,
            ];

        });
        //print_R($aDatas); exit();
        return $aDatas;
    }

    protected function _listMaigooKnowledge($crawler, $commonlist)
    {
        //print_r($commonlist->toArray());exit();
        $aDatas = [];
        $crawler->filter('.itembox .item')->each(function ($subNode) use (& $aDatas, $commonlist) {
            $sourceUrl = $this->getCrawlerElem($subNode, '.contbox a', 'href', 'attr');
            if (empty($sourceUrl)) {
                return ;
            }
			$sourceId = $this->getSourceId($sourceUrl);
            $name = $this->getCrawlerElem($subNode, '.contbox a', '', 'text');
            $thumb = $this->getCrawlerElem($subNode, '.img a img', 'src', 'attr');
            $thumb = str_replace('_220_135.jpg', '', $thumb);
            $description = $this->getCrawlerElem($subNode, '.description', '', 'text');
            $tagStr = $this->getCrawlerTag($subNode, '.other a');

            $aDatas[] = [
                'source_url' => $sourceUrl,
                'name' => $name,
                'code' => $sourceId,
                'code_ext' => $commonlist['code_ext'],
                'brand_code' => $commonlist['code_ext'],
                'description' => $description,
                'tag' => $tagStr,
                'source_id' => $sourceId,
                'content' => '',
                'thumb' => $thumb,
            ];

        });
        //print_R($aDatas); exit();
        return $aDatas;
    }

    protected function _listMaigooLeague($crawler, $commonlist)
    {
        print_r($commonlist->toArray());exit();
        $aDatas = [];
        $crawler->filter('.articlelist .itembox .item')->each(function ($subNode) use (& $aDatas, $commonlist) {
            $sourceUrl = $this->getCrawlerElem($subNode, '.contbox a', 'href', 'attr');
            if (empty($sourceUrl)) {
                return ;
            }
			$sourceId = $this->getSourceId($sourceUrl);
            $name = $this->getCrawlerElem($subNode, '.contbox a', '', 'text');
            $thumb = $this->getCrawlerElem($subNode, '.img a img', 'src', 'attr');
            $thumb = str_replace('_220_135.jpg', '', $thumb);
            $description = $this->getCrawlerElem($subNode, '.description', '', 'text');
            $tagStr = $this->getCrawlerTag($subNode, '.other a');

            $aDatas[] = [
                'source_url' => $sourceUrl,
                'name' => $name,
                'code' => $sourceId,
                'code_ext' => $commonlist['code_ext'],
                'brand_code' => $commonlist['code_ext'],
                'description' => $description,
                'tag' => $tagStr,
                'source_id' => $sourceId,
                'content' => '',
                'thumb' => $thumb,
            ];

        });
        //print_R($aDatas); exit();
        return $aDatas;
    }

    protected function _listMaigooArticle($crawler, $commonlist)
    {
        //print_r($commonlist->toArray());exit();
        $aDatas = [];
        $crawler->filter('.articlelist .itembox .item')->each(function ($subNode) use (& $aDatas, $commonlist) {
            $sourceUrl = $this->getCrawlerElem($subNode, '.contbox a', 'href', 'attr');
            if (empty($sourceUrl)) {
                return ;
            }
			$sourceId = $this->getSourceId($sourceUrl);
            $name = $this->getCrawlerElem($subNode, '.contbox a', '', 'text');
            $thumb = $this->getCrawlerElem($subNode, '.img a img', 'src', 'attr');
            $thumb = str_replace('_220_135.jpg', '', $thumb);
            $description = $this->getCrawlerElem($subNode, '.description', '', 'text');
            $tagStr = $this->getCrawlerTag($subNode, '.other a');

            $aDatas[] = [
                'source_url' => $sourceUrl,
                'name' => $name,
                'code' => $sourceId,
                'code_ext' => $commonlist['code_ext'],
                'brand_code' => $commonlist['code_ext'],
                'description' => $description,
                'tag' => $tagStr,
                'source_id' => $sourceId,
                'content' => '',
                'thumb' => $thumb,
            ];

        });
        //print_R($aDatas); exit();
        return $aDatas;
    }

    protected function _listMaigooStore($crawler, $commonlist)
    {
        //print_r($commonlist->toArray());exit();
        $aDatas = [];
        $crawler->filter('.searchselect .itembox .item')->each(function ($subNode) use (& $aDatas, $commonlist) {
            $source = $subNode->filter('.title a');
            $sourceUrl = $source->attr('href');
			$sourceId = md5($sourceUrl);
            $thumb = $this->getCrawlerElem($subNode, '.img a img', 'src', 'attr');
            $thumb = str_replace('_292_292.jpg', '', $thumb);
            $name = $subNode->filter('.title a')->text();
            $sort = $this->getCrawlerElem($subNode, '.cont .type', '', 'text');
            $sort = str_replace('类型：', '', $sort);
            $website = $this->getCrawlerElem($subNode, '.cont .adress', '', 'text');
            $website = str_replace('网址：', '', $website);
            $phone = $this->getCrawlerElem($subNode, '.cont .phone', '', 'text');
            $phone = str_replace('电话：', '', $phone);
            $level = $this->getCrawlerElem($subNode, '.cont .other', '', 'text');
            $level = str_replace('等级：', '', $level);
            //echo $logo . '---' . $name . '---' . $sourceUrl . '---' . $name . '--' . $level . '--' . $type . '---' . $mallIcon . '<br />';
            $aDatas[] = [
                'source_url' => $sourceUrl,
                'name' => $name,
                'code' => $sourceId,
                'code_ext' => $commonlist['code_ext'],
                'brand_code' => $commonlist['code_ext'],
                'sort' => $sort,
                'website' => $website,
                'phone' => $phone,
                'level' => $level,
                'source_id' => $sourceId,
                'content' => '',
                'thumb' => $thumb,
            ];

        });
        //print_R($aDatas); exit();
        return $aDatas;
    }

    protected function _listMaigooProduct($crawler, $commonlist)
    {
        //print_r($commonlist->toArray());exit();
        $aDatas = [];
        $crawler->filter('.searchselect .itembox .item')->each(function ($subNode) use (& $aDatas, $commonlist) {
            $source = $subNode->filter('.imgbox a');
            $sourceUrl = $source->attr('href');
			$sourceId = md5($sourceUrl);
            $thumb = $source->filter('img')->attr('src');
            $thumb = str_replace('_292_292.jpg', '', $thumb);
            $name = $subNode->filter('.title a')->text();
            $price = $this->getCrawlerElem($subNode, '.price', '', 'text');
            $price = str_replace('￥', '', $price);
            $level = $this->getCrawlerElem($subNode, '.shoplevel .c999', '', 'text');
            $shopUrl = $this->getCrawlerElem($subNode, '.shopurl a', 'href', 'attr');
            $shopName = $this->getCrawlerElem($subNode, '.shopurl a', '', 'text');
            //echo $logo . '---' . $name . '---' . $sourceUrl . '---' . $name . '--' . $level . '--' . $type . '---' . $mallIcon . '<br />';
            $aDatas[] = [
                'source_url' => $sourceUrl,
                'name' => $name,
                'code' => $sourceId,
                'code_ext' => $commonlist['code_ext'],
                'brand_code' => $commonlist['code_ext'],
                'price' => $price,
                'buy_url' => $sourceUrl,
                'shop_url' => $shopUrl,
                'shop_name' => $shopName,
                'source_id' => $sourceId,
                'content' => '',
                'thumb' => $thumb,
            ];

        });
        //print_R($aDatas); exit();
        return $aDatas;
    }

    protected function _listMaigooWebsite($crawler, $commonlist)
    {
        //print_r($commonlist->toArray());exit();
        $aDatas = [];
        $crawler->filter('.searchselect .itembox .item')->each(function ($subNode) use (& $aDatas, $commonlist) {
            $source = $subNode->filter('.imgbox a');
            $sourceUrl = $source->attr('href');
            $sourceId = basename($sourceUrl);
			$sourceId = str_replace('.html', '', $sourceId);
            $logo = $source->filter('img')->attr('src');
            $name = $subNode->filter('.webtitle a')->text();
            $mallIcon = $this->getCrawlerElem($subNode, '.shoplevel img', 'src');
            $type = $this->getCrawlerElem($subNode, '.shoplevel .typename', '', 'text');
            $level = $this->getCrawlerElem($subNode, '.shoplevel .c999', '', 'text');
            //echo $logo . '---' . $name . '---' . $sourceUrl . '---' . $name . '--' . $level . '--' . $type . '---' . $mallIcon . '<br />';
            $aDatas[] = [
                'source_url' => $sourceUrl,
                'name' => $name,
                'code' => $sourceId,
                'code_ext' => $commonlist['code_ext'],
                'brand_code' => $commonlist['code_ext'],
                'mall_icon' => $mallIcon,
                'type' => $type,
                'level' => $level,
                'source_id' => $sourceId,
                'content' => '',
                'logo' => $logo,
            ];

        });
        //print_R($aDatas); exit();
        return $aDatas;
    }

    protected function _listMaigooBrandtop($crawler, $commonlist)
    {
        print_r($commonlist->toArray());exit();
        $aDatas = [];
        $crawler->filter('.brandboxinfo .itembox')->each(function ($subNode) use (& $aDatas, $commonlist) {
            $source = $subNode->filter('.topinfo a');
            //print_R($source);exit();
            $sourceUrl = $source->attr('href');
            $sourceId = basename($sourceUrl);
			$sourceId = str_replace('.html', '', $sourceId);
            $logo = $source->filter('.img img')->attr('src');
            $name = $source->filter('.bname')->text();
            echo $logo . '---' . $name . '---' . $sourceUrl . '---' . $name . '<br />';
            $aDatas[] = [
                'source_url' => $sourceUrl,
                'name' => $name,
                'code' => $sourceId,
                'code_ext' => $commonlist['code_ext'],
                'source_id' => $sourceId,
                'content' => '',
                'logo' => $logo,
            ];

        });
        return $aDatas;
        print_R($aDatas);
        exit();

    }
    protected function _listMaigooSubject($crawler, $commonlist)
    {
        $datas = [];
        $i = 1;
        $lists = [];
        $crawler->filter('li')->each(function ($node) use (& $datas, $commonlist, & $i, & $lists) {
            $baseElem = $node->filter('a');
            $sourceUrl = $baseElem->attr('href');
            $sourceId = str_replace('.html', '', basename($sourceUrl));

            $name = trim($baseElem->text());

            $logo = $node->filter('img')->attr('src');
            $datas[] = [
                'source_url' => $sourceUrl,
                'name' => $name,
                'code' => $sourceId,
                'source_id' => $sourceId,
                'content' => '',
                'logo' => $logo,
            ];
            if (!in_array($sourceUrl, $lists)) {
                echo $i . '==' . $name . '--' . $sourceUrl . '--<br />';
                $i++;
            } else {
                //echo 'nnnnn-' . $i . '==' . $name . '--<br />';
            }
            $lists[] = $sourceUrl;
            $listData = ['page' => 1, 'name' => $name, 'code' => $sourceId, 'url' => $sourceUrl];
            //print_r($listData);
            $spider = $this->_getPointModelData('spiderinfo', 38);
            $this->_writeList($listData['url'], $listData['page'], $listData, $spider);
        });
        exit();
        return false;
        //print_r($datas);exit();
        return $datas;
    }

    protected function _listMaigooBrand_storebak($crawler, $commonlist)
    {
        //UPDATE `wp_brand_store` AS `bs`, `wp_brand` AS `b` SET `bs`.`mall_id` = `b`.`id` WHERE `bs`.`extfield` = `b`.`code` 
        //print_r($commonlist);
        $datas = [];
        $crawler->filter('.searchselect .itembox li')->each(function ($node) use (& $datas, $commonlist) {
            $baseElem = $node->filter('.imgbox a');
            if (count($baseElem) <= 0) {
                return ;
            }
            $sourceUrl = $baseElem->attr('href');
            $sourceId = str_replace('.html', '', basename($sourceUrl));

            $name = $node->filter('.webtitle a')->text();
            $title = $node->filter('.typename')->text();

            $thumb = $node->filter('.imgbox img')->attr('src');
            $mallUrl = $node->filter('.buybtn a')->attr('href');
            //$mallUrl = substr($mallUrl, strpos('?', $mallUrl));
            $datas[] = [
                'source_url' => $sourceUrl,
                'title' => trim($title),
                'name' => trim($name),
                'code' => $sourceId,
                //'category_code' => $commonlist['code'],
                'extfield' => $commonlist['code'],
                'source_id' => $sourceId,
                'mall_url' => $mallUrl,
                'thumb' => $thumb,
            ];
        });
        return $datas;
    }

    protected function _listMaigooProductbak($crawler, $commonlist)
    {
        $datas = [];
        //print_r($commonlist->toArray());
        $crawler->filter('.searchselect .itembox li')->each(function ($node) use (& $datas, $commonlist) {
            $baseElem = $node->filter('.imgbox a');
            $sourceUrl = $baseElem->attr('href');
            $sourceId = str_replace('item.htm?id=', '', basename($sourceUrl));

            $name = $node->filter('.title a')->text();

            $thumb = $node->filter('.imgbox img')->attr('src');
            $thumb = str_replace('_292_292.jpg', '', $thumb);
            $price = trim($node->filter('.price')->text());
            $price = str_replace('￥', '', $price);
            $extfield2 = trim($node->filter('.shopurl a')->text());
            $datas[] = [
                'source_url' => $sourceUrl,
                'extfield' => $sourceUrl,
                'title' => trim($name),
                'name' => trim($name),
                'description' => $sourceId,
                //'code' => $sourceId,
                'price' => $price,
                'extfield2' => $extfield2,
                'brand_code' => $commonlist['code'],
                //'category_code' => $commonlist['code'],
                'source_id' => $sourceId,
                'thumb' => $thumb,
            ];
            /*$commoninfo = $this->getPointModel('commoninfo')->getInfo($sourceId, 'source_id');
            $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, $this->info_table);
            $info = $targetModel->getInfo($commoninfo->target_id);
            $info->price = $price;
            $info->extfield2 = $extfield2;
            $info->update(false, ['price', 'extfield2']);*/

        });
        //print_r($datas);exit();
        return $datas;
    }

    protected function _listMaigooBrandbak($crawler, $commonlist)
    {
        $datas = [];
        $crawler->filter('.itembox li')->each(function ($node) use (& $datas, $commonlist) {
            //print_r($node);exit();
            $baseElem = $node->filter('a');
            $sourceUrl = $baseElem->attr('href');
            if (strpos($sourceUrl, '/brand/') === false && strpos($sourceUrl, '/special/wenti/') === false) {
                return ;
            }
            $title = $baseElem->text();
            $thumb = $node->filter('img');
            $thumb = count($thumb) > 0 ? $thumb->attr('src') : '';
            $sourceId = str_replace('.html', '', basename($sourceUrl));
            $datas[] = [
                'source_url' => $sourceUrl,
                'title' => trim($title),
                'name' => trim($title),
                'code' => $sourceId,
                'category_code' => $commonlist['code'],
                'source_id' => $sourceId,
                'thumb' => $thumb,
            ];
        });
        return $datas;
    }
}
