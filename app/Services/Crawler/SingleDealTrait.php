<?php

declare(strict_types = 1);

namespace ModuleBench\Services\Crawler;

use Overtrue\Pinyin\Pinyin;

trait SingleDealTrait
{
    public function _singleTmpTmpsingle($crawler)
    {
        $i = 0;
        $datas = [];
        $crawler->filter('table')->each(function ($node) use (& $datas, & $i) {
            $text = $node->text();
            $datas[$i] = [];
            $j = 0;
            $node->filter('tr')->each(function ($subNode) use (& $datas, $i, & $j) {
                $firstNode = $subNode->filter('th');
                $first = '';
                if (count($firstNode) > 0) {
                    $first = trim($firstNode->text());
                }
                $datas[$i][$j] = [$first];
                $subNode->filter('td')->each(function ($subNode) use (& $datas, $i, $j) {
                    $text = $subNode->html();
                    $datas[$i][$j][] = trim($text);
                });
                $j++;
            });
            $i++;
        });
        var_export($datas);
        exit();
    }

    public function _singleNavNav($crawler, $firstCode = '')
    {
        $i = 0;
        $sqlElem = '';
        $codes = [];
        $datas = [];
        $sql = "INSERT INTO `wp_navsort` (`name`, `code`, `description`, `parent_code`, `icon`, `icon_color`) VALUES ('推荐', '{$firstCode}tj','', '{$firstCode}', ''),<br />";
        $crawler->filter('.containertj .col')->each(function ($node) use (& $sql, & $i, & $codes, & $datas) {
            $j = 10000;
                $name = trim($node->text());
                $code = Pinyin::letter($name, ['delimiter' => '', 'accent' => true]);
                if (in_array($code, $codes)) {
                    $code .= rand(1, 1000);
                }
                $codes[] = $code;
                $url = trim($node->filter('a')->attr('href'));
                /*$icon = trim($node->filter('i')->attr('class'));
                $icon = str_replace('fa ', '', $icon);
                $iColor = trim($node->filter('i')->attr('style'));*/
                $logoPath = $node->filter('img')->attr('src');
                $sort = 'searchtj';
                $datas[] = [
                    'name' => $name,
                    'code' => $code,
                    'url' => $url,
                    'logo_path' => $logoPath,
                    //'icon' => $icon, 
                    //'iColor' => $iColor,
                ];
                //$sql .= "('{$name}', '{$code}', '{$url}', '', '{$icon}', '{$iColor}'),<br />";
                //echo $code . '--' . $num . '--' . $icon . '--' . $name . '==' . $url . '--' . $sort . '<br />';
                $j--;
            $i++;
            //echo "<br />";
        });
        var_export($datas);exit();
        echo $sql;exit();
        return [trim($sql, ',<br />') . ';<br />', trim($sqlElem, ',<br />') . ';<br />', $command];
    }

    public function _singleNavNavsitenew($crawler, $firstCode)
    {
        $str = '';
        //$firstCode = 'design';
        $sql = "INSERT INTO `wp_navsort` (`name`, `code`, `description`, `parent_code`, `icon`) VALUES ('推荐', '{$firstCode}tj','', '{$firstCode}', ''),<br />";
        $sqlElem = 'INSERT INTO `wp_navigation` (`name`, `code`, `description`, `sort`, `logo_type`, `orderlist`, `website`, `extfield`) VALUES <br />';
        $i = 1;
        $command = '';
        $sorts = [$firstCode . 'tj'];
        $crawler->filter('.colsubs')->each(function ($node) use (& $sql, & $sorts, $firstCode, & $sqlElem) {
            $sortCode = '';
        $node->filter('.tit')->each(function ($subNode) use (& $sql, & $sorts, $firstCode, & $sortCode) {
            $icon = trim($subNode->filter('i')->attr('class'));
            $nameSource = trim($subNode->text());
            $nameSource = str_replace(' · ', '·', $nameSource);
            $icon = str_replace('fa ', '', $icon);
            $name = $nameSource;
            $description = '';
            $name = trim($name);
            $code = Pinyin::letter($name, ['delimiter' => '', 'accent' => true]);
            if (in_array($code, $sorts)) {
                $code .= rand(1, 1000);
            }
            $sorts[] = $code;
            $sortCode = $code;

            $sql .= "('{$name}', '{$code}', '{$description}', '{$firstCode}', '{$icon}'),<br />";
            //echo $icon . '--' . $id . '==' . $nameSource . '--' . $name . '==' . $description . '<br />';

        });
            echo $sortCode . '<br />';
        //echo $sql;
        $i = 0;
        $codes = [];
        $node->filter('.row-cols-lg-3')->each(function ($node) use (& $sqlElem, & $command, & $i, $sorts, & $codes, $sortCode) {
            echo 'oooooooooooo';
            $j = 10000;
            $node->filter('.col a')->each(function ($subNode) use (& $sqlElem, & $command, $i, & $j, $sorts, & $codes, $sortCode) {
                $name = trim($subNode->text());
                $code = Pinyin::letter($name, ['delimiter' => '', 'accent' => true]);
                if (in_array($code, $codes)) {
                    $code .= rand(1, 1000);
                }
                $codes[] = $code;
                $url = trim($subNode->attr('href'));
                $iconObj = $subNode->filter('img');
                $num = count($iconObj);
                $icon = $num ? trim($iconObj->attr('src')) : '';
                $logoType = '';
                if ($icon) {
                    $extName = substr($icon, strpos($icon, '.'));
                    $logoType = str_replace('.', '', $extName);
                    $command .= "mv /data/htmlwww/filesys/spider/pages/{$icon} /data/htmlwww/filesys/spider/pages/nav/icons/{$code}{$extName}<br />";
                }
                $sort = $sortCode;
                $sqlElem .= "('{$name}', '{$code}', '', '{$sort}', '{$logoType}', {$j}, '{$url}', '{$icon}'),<br />";
                //echo $code . '--' . $num . '--' . $icon . '--' . $name . '==' . $url . '--' . $sort . '<br />';
                $j--;
            });
            $i++;
            //echo "<br />";
        });
        });
        return [trim($sql, ',<br />') . ';<br />', trim($sqlElem, ',<br />') . ';<br />', $command];
        echo $sql;echo $sqlElem;echo $command;exit();
    }

    public function _singleNavNavsiteold($crawler, $firstCode)
    {
        $str = '';
        //$firstCode = 'design';
        $sql = "INSERT INTO `wp_navsort` (`name`, `code`, `description`, `parent_code`, `icon`) VALUES ('推荐', '{$firstCode}tj','', '{$firstCode}', ''),<br />";
        $i = 1;
        $command = '';
        $sorts = [$firstCode . 'tj'];
        $sql = '';
        $crawler->filter('.tit')->each(function ($node) use (& $sql, & $sorts, $firstCode) {
            $icon = trim($node->filter('i')->attr('class'));
            $id = trim($node->attr('id'));
            $nameSource = trim($node->text());
            $nameSource = str_replace(' · ', '·', $nameSource);
            $icon = str_replace('fa ', '', $icon);
            if (false) {//strpos($nameSource, ' ') !== false) {
                var_dump(explode(' ', $nameSource));
                //list($name, $a, $b, $c, $description) = explode(' ', $nameSource);
                $name = $description = '';
            } else {
                $name = $nameSource;
                $description = '';
            }
            $name = trim($name);
            $code = Pinyin::letter($name, ['delimiter' => '', 'accent' => true]);
            if (in_array($code, $sorts)) {
                $code .= rand(1, 1000);
            }
            $iColor = trim($node->filter('i')->attr('style'));

            $description = trim($description);
            $sorts[] = $code;

            //$sql .= "('{$name}', '{$code}', '{$description}', '{$firstCode}', '{$icon}'),<br />";
            $sql .= "UPDATE `wp_navsort` SET `icon_color` = '{$iColor}' WHERE `code` = '{$code}';<br />";
            //echo $icon . '--' . $id . '==' . $nameSource . '--' . $name . '==' . $description . '<br />';

        });
        //echo $sql;
        $i = 0;
        $sqlElem = 'INSERT INTO `wp_navigation` (`name`, `code`, `description`, `sort`, `logo_type`, `orderlist`, `website`, `extfield`) VALUES <br />';
        $codes = [];
        $crawler->filter('.row-cols-lg-6')->each(function ($node) use (& $sqlElem, & $command, & $i, $sorts, & $codes) {
            return ;
            $j = 10000;
            $node->filter('.col a')->each(function ($subNode) use (& $sqlElem, & $command, $i, & $j, $sorts, & $codes) {
                $name = trim($subNode->text());
                $code = Pinyin::letter($name, ['delimiter' => '', 'accent' => true]);
                if (in_array($code, $codes)) {
                    $code .= rand(1, 1000);
                }
                $codes[] = $code;
                $url = trim($subNode->attr('href'));
                $iconObj = $subNode->filter('img');
                $num = count($iconObj);
                $icon = $num ? trim($iconObj->attr('src')) : '';
                $logoType = '';
                if ($icon) {
                    $extName = substr($icon, strpos($icon, '.'));
                    $logoType = str_replace('.', '', $extName);
                    $command .= "mv /data/htmlwww/filesys/spider/pages/{$icon} /data/htmlwww/filesys/spider/pages/nav/icons/{$code}{$extName}<br />";
                }
                $sort = $sorts[$i] ?? 'nooo';
                $sqlElem .= "('{$name}', '{$code}', '', '{$sort}', '{$logoType}', {$j}, '{$url}', '{$icon}'),<br />";
                //echo $code . '--' . $num . '--' . $icon . '--' . $name . '==' . $url . '--' . $sort . '<br />';
                $j--;
            });
            $i++;
            //echo "<br />";
        });
        return [trim($sql, ',<br />') . '<br />', trim($sqlElem, ',<br />') . ';<br />', $command];
        echo $sql;echo $sqlElem;echo $command;exit();
    }

    public function _singleCultureCategory($crawler)
    {
        $str = '';
        $sql = 'INSERT INTO `wp_culture_category` (`name`, `code`, `description`, `parent_code`) VALUES ';
        $i = 1;
        $text = $textMark = '';
        $crawler->filter('.topnavNum7 .b')->each(function ($node) use (& $sql, & $text, & $textMark) {
            $first = $node->filter('.itemMenu a');
            $firstName = $first->text();
            $firstUrl = $first->attr('href');
            $firstUrl = 'http://www.yac8.com/' . ltrim($firstUrl, '.');
            $code = Pinyin::letter($firstName, ['delimiter' => '', 'accent' => false]);
            $sql .= "('{$firstName}', '{$code}', '{$firstUrl}', ''),<br />\n";
            echo $firstName . '-' . $firstUrl . '-' . $code . '<br />';
            $text .= "['page' => 1, 'name' => '{$firstName}', 'code' => '{$code}', 'url' => '{$firstUrl}'],<br />\n";
            $textMark .= "<a href='{$firstUrl}' target='_blank'>{$firstName}-{$code}</a><br />\n";
            $node->filter('.subnav a')->each(function ($subNode) use (& $sql, $code, & $text, & $textMark) {
                $subUrl = $subNode->attr('href');
                $subUrl = 'http://www.yac8.com/' . ltrim($subUrl, '.');
                $subName = $subNode->text();

                $subCode = Pinyin::letter($subName, ['delimiter' => '', 'accent' => false]);

                $sql .= "('{$subName}', '{$subCode}', '{$subUrl}', '{$code}'),<br />\n";
                $text .= "['page' => 1, 'name' => '{$subName}', 'code' => '{$subCode}', 'url' => '{$subUrl}'],<br />\n";
                $textMark .= "<a href='{$subUrl}' target='_blank'>{$subName}-{$subCode}</a><br />\n";
                //$sql .= "('{$subName}', '{$subCode}', '', '{$subUrl}', '{$code}', 0),<br />\n";


            });
        });
        echo $text;
        echo $textMark;
                echo $sql;exit();
        echo $sql;exit();
    }

    public function _singlePetinfoCategorySelf($crawler)
    {
        $str = '';
        //$sql = 'INSERT INTO `wp_pet_sort` (`name`, `code`, `description`, `parent_code`) VALUES ';
        //$sql = 'INSERT INTO `wp_pet_sort` (`name`, `code`, `description`, `title`) VALUES ';
        $sql = 'INSERT INTO `wp_pet_sort` (`name`, `code`, `type`, `description`, `title`, `is_master`) VALUES ';
        $i = 1;
        $text = $textMark = '';
        $crawler->filter('.nav_auto li')->each(function ($node) use (& $sql, & $text, & $textMark) {
            $iName = $node->filter('a');
            $firstNode = $node->filter('p a');
            $url = $firstNode->attr('href');
            $name = $firstNode->text();
            $code = Pinyin::letter($name, ['delimiter' => '', 'accent' => false]);
            $sql .= "('{$name}', '{$code}', '{$name}', '{$url}'),<br />\n";
            //echo $name . '-' . $url . '-' . $code;
            $node->filter('.nav_list a')->each(function ($subNode) use (& $sql, $code, & $text, & $textMark) {
                $subUrl = $subNode->attr('href');
                $subName = $subNode->text();

                $subCode = Pinyin::letter($subName, ['delimiter' => '', 'accent' => false]);

                $sql .= "('{$subName}', '{$subCode}', '{$subUrl}', '{$code}'),<br />\n";
                $text .= "['page' => 1, 'name' => '{$subName}', 'code' => '{$subCode}', 'url' => '{$subUrl}'],<br />\n";
                $textMark .= "<a href='{$subUrl}' target='_blank'>{$subName}-{$subCode}</a><br />\n";
                //$sql .= "('{$subName}', '{$subCode}', '', '{$subUrl}', '{$code}', 0),<br />\n";


            });
                //echo $sql;exit();
        });
        echo $text;
        echo $textMark;exit();
        echo $sql;exit();
    }

    public function _singlePetinfoCategory($crawler)
    {
        $sql = 'INSERT INTO `wp_calligrapher` (`id`, `extfield`, `name`, `dynasty`, `description`) VALUES ';
        $crawler->filter('.table-responsive tbody tr')->each(function ($node) use (& $sql) {
            $id = trim($node->filter('.column-id')->text());
            $pic = trim($node->filter('.column-pic img')->attr('src'));
            $name = trim($node->filter('.column-name')->text());
            $dynasty = trim($node->filter('.column-dynasty_id')->text());
            $description = trim($node->filter('.column-introduction')->text());

            $sql .= "('{$id}', '{$pic}', '{$name}', '{$dynasty}', '{$description}'),\n";

            //echo $id . '==' . $pic . '==' . $name . '==' . $title . '==' . $wide . '==' . $high . '==' . $clarity . '==' . $isOriginal . '==' . $auther . '==' . $dynasty . '==' . $chirography . '==' . $pageNum . '==' . $wordNum . "\n";
        });
        echo $sql;exit();


        $sql = 'INSERT INTO `wp_rubbing` (`id`, `pic`, `ru_title`, `app_title`, `wide`, `high`, `clarity`, `is_original`, `ru_auther`, `ru_dynasty`, `chirography`, `page`, `words_num`) VALUES ';
        $crawler->filter('.grid-table tbody tr')->each(function ($node) use (& $sql) {
            $id = trim($node->filter('.column-id')->text());
            $pic = trim($node->filter('.column-pic img')->attr('src'));
            $name = trim($node->filter('.column-ru_title')->text());
            $title = trim($node->filter('.column-app_title')->text());
            $wide = trim($node->filter('.column-wide')->text());
            $high = trim($node->filter('.column-high')->text());
            $clarity = trim($node->filter('.column-clarity')->text());
            $isOriginal = trim($node->filter('.column-is_original')->text());
            $auther = trim($node->filter('.column-authers')->text());
            $dynasty = trim($node->filter('.column-ru_dynasty')->text());
            $chirography = trim($node->filter('.column-chirography')->text());
            $pageNum = trim($node->filter('.column-页数')->text());
            $pageNum = substr($pageNum, 0, strpos($pageNum, '/'));
            $wordNum = trim($node->filter('.column-单字')->text());
            $wordNum = substr($wordNum, 0, strpos($wordNum, '/'));
            $sql .= "('{$id}', '{$pic}', '{$name}', '{$title}', '{$wide}', '{$high}', '{$clarity}', '{$isOriginal}', '{$auther}', '{$dynasty}', '{$chirography}', '{$pageNum}', '{$wordNum}'),\n";

            //echo $id . '==' . $pic . '==' . $name . '==' . $title . '==' . $wide . '==' . $high . '==' . $clarity . '==' . $isOriginal . '==' . $auther . '==' . $dynasty . '==' . $chirography . '==' . $pageNum . '==' . $wordNum . "\n";
        });
        echo $sql;exit();
    }
}
