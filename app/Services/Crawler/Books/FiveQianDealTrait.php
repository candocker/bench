<?php

declare(strict_types = 1);

namespace ModuleBench\Services\Crawler\Books;

use Overtrue\Pinyin\Pinyin;

Trait FiveQianDealTrait
{
    protected function _single5000yan5000yan($crawler)
    {
        $i = 1;
        $datas = [];
        $sql = 'INSERT INTO `wp_commonlist` (`spiderinfo_id`, `name`, `code`, `code_ext`, `description`, `spider_num`, `spider_sourcenum`, `created_at`, `updated_at`, `source_site`, `source_url`, `source_page`, `status`) VALUES ';
        $crawler->filter('.main-content article')->each(function ($node) use ($sql, & $i, $spiderinfo) {
            $chapterName = $node->filter('h2')->text();
            $elems = $node->filter('.shi-jianju a')->each(function ($subNode) use ($sql, & $i, $chapterName, $spiderinfo) {
                $date = date('Y-m-d H:i:s');
                $name = $subNode->text();
                $url = $subNode->attr('href');
                $sql .= "({$spiderinfo['id']}, '{$name}', '{$chapterName}', '{$i}', '', 0, 0, '{$date}', '{$date}', '{$spiderinfo['code']}', '{$url}', 1, 1);";
                \DB::connection('bench')->select($sql);
                echo $sql;
                $i++;
            });
        });
        echo $sql;
        exit();
    }

    protected function _single5000yanChunqiulei($crawler, $spiderinfo)
    {
        $i = 1;
        $datas = [];

        $sql = 'INSERT INTO `wp_commoninfo` (`spiderinfo_id`, `target_id`, `name`, `code`, `created_at`, `updated_at`, `source_site`, `source_id`, `source_url`, `extfield`) VALUES ';
        $crawler->filter('.main-content article')->each(function ($node) use ($sql, & $i, $spiderinfo) {
            $chapterName = $node->filter('h2')->text();
            $elems = $node->filter('.shi-jianju a')->each(function ($subNode) use ($sql, & $i, $chapterName, $spiderinfo) {
                $date = date('Y-m-d H:i:s');
                $name = $subNode->text();
                $url = $subNode->attr('href');
                $sourceId = basename($url);
                $sql .= "({$spiderinfo['id']}, '0', '{$name}', '{$i}', '{$date}', '{$date}', '{$spiderinfo['code']}', '{$sourceId}', '{$url}', '{$chapterName}')";
                //\DB::connection('bench')->select($sql);
                echo $sql . "\n";
                $i++;
            });
        });
        //echo $sql;
        exit();
    }

	protected function _list5000yan5000yan($crawler, $commonlist)
    {
        $tags = $crawler->filter('.listtop div');
        $desc = [];
        foreach ($tags as $tag) {
            $value = trim($tag->nodeValue);
            if (empty($value)) {
                continue;
            }
            $desc[] = $value;
        }

        //print_r($desc);exit();
        $commonlist->description = json_encode($desc);
        $commonlist->save();
        return false;
        $sql = 'INSERT INTO `wp_commoninfo` (`spiderinfo_id`, `target_id`, `name`, `code`, `created_at`, `updated_at`, `source_site`, `source_id`, `source_url`) VALUES ';
        $crawler->filter('.blockGroup article h2')->each(function ($node) use ($sql, $commonlist) {
            $url = $node->filter('a')->attr('href');
            $date = date('Y-m-d H:i:s');
            $name = $node->text();
            $name = str_replace(['〖翻译〗'], [''], $name);
            $sourceId = basename($url);
            $sql .= "(5, '{$commonlist['id']}', '{$name}', '{$commonlist['id']}', '{$date}', '{$date}', '5000yan', '{$sourceId}', '{$url}')";
            \DB::connection('bench')->select($sql);
        });
        return false;
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

    public function createCommoninfo($crawler, $commoninfo)
    {
        $sql = 'INSERT INTO `wp_commoninfo` (`spiderinfo_id`, `target_id`, `name`, `code`, `created_at`, `updated_at`, `source_site`, `source_id`, `source_url`, `extfield`) VALUES ';
        $i = 3;
        $elems = $crawler->filter('.main-content a')->each(function ($node) use ($sql, & $i, $commoninfo) {
            $name = $node->text();
            $url = $node->attr('href');
            $date = date('Y-m-d H:i:s');
            $sourceId = basename($url);
            $exist = $commoninfo->where(['source_url' => $url, 'target_id' => $commoninfo['id']])->first();
            if (empty($exist)) {
            $sql .= "({$commoninfo['spiderinfo_id']}, '{$commoninfo['id']}', '{$name}', '{$i}', '{$date}', '{$date}', '{$commoninfo['source_site']}', '{$sourceId}', '{$url}', '{$commoninfo['relate_id']}')";
            \DB::connection('bench')->select($sql);
            //echo $sql . "\n";exit();
            $i++;
            }
        });

    }
}
