<?php

declare(strict_types = 1);

namespace ModuleBench\Controllers;

trait DealBookTrait
{
    /*public function dealBookByList($commonlist)
    {
        $code = $commonlist->code;
        $file = config('culture.material_path') . '/books/shangshu/' . $code . '.php';
        if (empty($commonlist->description)) {
            return true;
        }

        $ext = "'" . implode("','", json_decode($commonlist->description, true));
        //echo $ext;exit();
        $contents = file_get_contents($file);
        $contents .= $ext;
        file_put_contents($file, $contents);
        return true;
        if (file_exists($file)) {
            return true;
        }
        $infos = $this->getModelObj('commoninfo')->where(['target_id' => $commonlist->id])->orderBy('id', 'asc')->get();

        $descs = explode("\n", $commonlist->description);
        $str .= "'unscramble' => [\n";
        foreach ($descs as $desc) {
            $desc = trim($desc);
            if (empty($desc)) {
                continue;
            }
            $str .= "    '{$desc}',\n";
        }
        $str .= "],\n";

        $str = $this->formatContent($infos);
        file_put_contents($file, $str);
    }*/

    public function adjust()
    {
        $datas = $this->getBookInfos(null, true);
        $basePath = $this->getBasePath();
        $cancels = ['name', 'brief', 'nameShort', 'author', 'keyword', 'nameSpell'];
        foreach ($datas['books'] as $bookCode => $bData) {
            if (!in_array($bookCode, ['shijing'])) {
            //if (in_array($bookCode, ['yijing', 'yizhuan'])) {
            //if (in_array($bookCode, ['shijing', 'guwenguanzhi', 'daodejing', 'chuci', 'lunyu', 'daxue', 'mengzi', 'xunzi', 'zhuangzi', 'zhongyong', 'mozi', 'yizhuan'])) {
                continue;
            }
            //$file = $basePath . "book/{$bookCode}_catalogue.php";
            $chapters = $this->getChapterInfos($bookCode);
            foreach ($chapters['infos'] as $cCode => $cData) {
                $dFile = $basePath . "{$bookCode}/{$cCode}.php";
                if (isset($cData['isLost'])) {
                    continue;
                }
                $content = file($dFile);
                foreach ($content as $index => & $iValue) {
                    if (strpos($iValue, "'name' => '第节'") !== false) {
                        //$iValue = str_replace('第节', '白话', $iValue);
                    }
                    if ($index > 10) {
                        continue;
                    }

                    foreach ($cancels as $cancel) {
                        if (strpos($iValue, "    '{$cancel}' => ") !== false) {
                            unset($content[$index]);
                        }
                    }
                }
                $nContent = implode('', $content);
                file_put_contents($dFile, $nContent);
            }
            //print_r($chapters);exit();

            /*$str = "<?php\nreturn [\n";
            $str .= "    '{$rKey}' => [\n        'code' => '{$rKey}',\n    ],\n";
            $str .= "];";
            file_put_contents($file, $str);*/
        }
        //echo $str;exit();
        //print_r($datas);exit();
    }

    public function dealYijing()
    {
        $infos = require($this->getBasePath() . '/booklist/yijing_catalogue.php');
        $str = "<?php\n";
        $str .= "return [\n";
        foreach ($infos as $key => $info) {
            $detail = $this->dealDivination($info['symbol']);
            $symbolStr = implode(',', $info['symbol']);
            $str .= "    '{$key}' => [\n";
            $str .= "        'code' => '{$info['code']}', 'serial' => {$info['serial']}, 'binSerial' => {$info['binSerial']}, 'name' => '{$info['name']}', 'spell' => '{$info['spell']}', 'brief' => '{$info['brief']}',\n";
            $str .= "        'down' => '{$detail['down']}', 'up' => '{$detail['up']}', 'downOther' => '{$detail['downOther']}', 'upOther' => '{$detail['upOther']}', 'symbol' => [{$symbolStr}],\n";
            $str .= "    ],\n";
        }
        $str .= "];";
        file_put_contents('/data/database/material/booklist/yijing_catalogue.php', $str);
        
        print_r($infos);exit();

    }

    protected function dealDivination($symbol)
    {
        $divinations = ['坤', '震', '坎', '兑', '艮', '离', '巽', '乾'];
        $divinationOthers = ['地', '雷', '水', '泽', '山', '火', '风', '天'];
        $split = array_chunk($symbol, 3);
        $downIndex = bindec(implode('', array_reverse($split[0])));
        $upIndex = bindec(implode('', array_reverse($split[1])));

        $data = [
            'down' => $divinations[$downIndex],
            'up' => $divinations[$upIndex],
            'downOther' => $divinationOthers[$downIndex],
            'upOther' => $divinationOthers[$upIndex],
            'binSerial' => bindec(implode('', array_reverse($symbol))),
        ];
        return $data;
    }

}
