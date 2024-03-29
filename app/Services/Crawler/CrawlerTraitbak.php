<?php

declare(strict_types = 1);

namespace ModuleBench\Services\Crawler;

trait CrawlerTrait
{
    use CurlTrait;
    //use \ModuleBench\Services\Crawler\Books\FiveQianDealTrait;

    public function _listDeal($crawler, $commonlist)
    {
        $datas = [];
        $i = 1;
        $selectStr = $commonlist['list_first'];
        $crawler->filter($selectStr)->each(function ($node) use (& $datas, $commonlist, & $i) {
            $method = $commonlist->getCustomMethod('first');
            $method = method_exists($this, $method) ? $method : 'listFirst';
            $firstData = $this->$method($node, $commonlist);
            $listSecond = $commonlist['list_second'];
            if (empty($listSecond)) {
                $datas[] = $this->foramtData($firstData);
                $i++;
                return ;
            }

            $node->filter($listSecond)->each(function ($subNode) use ($firstData, & $datas, $commonlist, & $i) {
                $method = $commonlist->getCustomMethod('first');
                $method = method_exists($this, $method) ? $method : 'listFirst';
                $secondData = $this->$method($node, $commonlist);

                $i++;
            });
        });

        //print_r($datas);exit();
        return $datas;
    }

    //protected function _info5000yan5000yan($crawler, $commoninfo)
    //protected function _info5000yanChunqiulei($crawler, $commoninfo)
    protected function _info5000yanFive($crawler, $commoninfo)
    {
        //echo $commoninfo->source_url;
        //$this->createCommoninfo($crawler, $commoninfo);
        //exit();
        //return false;

        /*$content = '';
        $spell = '';
        $pres = $crawler->filter('ruby')->each(function ($node) use (& $content, & $spell, $commoninfo) {
            $str = trim($node->text());
            $str2 = trim($node->filter('rt')->text());
            $str = str_replace($str2, '', $str);
            $content .= $str;
            $spell .= $str2 . (in_array($str, ['，', '。']) ? $str : ' ');
            
        });
        $spell = str_replace([' ，', ' 。'], ['，', '。'], $spell);*/

        $sStr = $crawler->filter('.section-body .grap')->html();
        $sStr = strip_tags($sStr);
        $infos = explode("\n", $sStr);
        //print_r($infos);

        $images = [];
        /*$crawler->filter('.grap img')->each(function ($node) use (& $images) {
            $iUrl = $node->attr('data-wuqianyan');
            $images[] = '<img src="' . $iUrl . '" />';
        });*/

        $elems = $crawler->filter('.section-body .grap')->children();
        //$elems = $crawler->filter('.section-body .grap div');
        $datas = [];//'content' => [$content], 'spell' => [$spell]];
        $key = 'content';
        $keys = [
            '【原文】' => 'content', 
            '【原文】' => 'content',
            '【注释】' => 'notes', 
            '【翻译】' => 'vernacular', 
            '【译读】' => 'vernacular', 
            '【译文】' => 'vernacular', 
            '【解释】' => 'vernacular',
            '【按语】' => 'unscramble',
            '【实例解读】' => 'unscramble',
            '【解读】' => 'unscramble',
        ];
        $olds = ['〔', '〕', '[', ']', '（', '）', '①','②','③','④','⑤','⑥','⑦','⑧','⑨','⑩','⑪','⑫','⑬','⑭', '⑮', '⑯', '⑰', '⑱', '⑲', '⑳', '㉑', '㉒', '㉓', '㉔', '㉕', '㉖'];
        $news = ['(', ')', '(', ')', '(', ')', '(1)','(2)','(3)','(4)','(5)', '(6)', '(7)', '(8)', '(9)', '(10)', '(11)', '(12)', '(13)', '(14)', '(15)', '(16)', '(17)', '(18)', '(19)', '(20)', '(21)', '(22)', '(23)', '(24)', '(25)', '(26)'];
        //foreach ($elems as $elem) {
            //$value = trim($elem->nodeValue);
        foreach ($infos as $value) {
            $value = trim(str_replace([' '], [''], $value));
            if (empty($value)) {
                continue;
            }
            if (isset($keys[$value])) {
                $key = $keys[$value];
                continue;
            }
            $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
            $value = str_replace($olds, $news, $value);
            $datas[$key][] = $value;
            if (strpos($value, '【翻译】') !== false) {
                $key = 'vernacular';
            }
        }
        $datas['vernacular'] = isset($datas['vernacular']) ? array_merge($datas['vernacular'], $images) : $images;
        //print_r($datas);exit();
        $commoninfo->code_ext = json_encode($datas);
        $commoninfo->save();
        return true;
    }

    public function listFirst($node, $commonlist)
    {
        $datas = [
            'sort' => $node->filter('h2')->text(),
            'description' => $node->filter('h2')->text(),
        ];
        return $datas;
            //$chapter = trim($node->filter('h2')->text());
            $chapter = '';//trim($node->filter('.menu-item-object-category p')->text());
            $description = '';//trim($node->filterXPath('//div[contains(@class,"shi-jianju")]')->text());
    }

    public function listSecond($node, $commonlist)
    {
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
            'list_id' => $commonlist['id'],
            'extfield' => $chapter,
            'title' => $description,
            'source_id' => $sourceId,
        ];

        $sourceId = basename($sourceUrl);
        $sourceId = str_replace('.html', '', $sourceId);
        $name = $baseObj->text();
        $name = str_replace(['大戴礼记·', '尔雅·'], ['', ''], $name);
        $datas[] = [
            'source_url' => $sourceUrl,
            'name' => $name,
            'code' => $i,
            'name' => $name,
            'list_id' => $commonlist['id'],
            //'code_ext' => $name,
            'source_id' => $sourceId,
        ];
        $i++;
    }
}
