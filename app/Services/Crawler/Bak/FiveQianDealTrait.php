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
