<?php

declare(strict_types = 1);

namespace ModuleBench\Services\Crawler;

trait FiveTrait
{
    public function getPointElem($commonlist, $type)
    {
        $siteCode = $commonlist->spiderinfo->code;
        $siteMethod = "_{$siteCode}Elems";
        $data = $this->$siteMethod()[$commonlist->code];
        if ($type == 'info') {
            return $this->formatElem($siteCode, $data[$type]);
        }

        if ($type == 'list') {
            $elem = $this->formatElem($siteCode, $data[$type]);
            if (isset($data['middle'])) {
                $elem['fields']['is_middle'] = 1;
            }
            if (isset($data['subFilter'])) {
                $elem['subFilter'] = $this->formatElem($siteCode, $data['subFilter']);
            }
            return $elem;
        }

        if ($type == 'middle') {
            $elem = [];
            if (isset($data['middle'][0])) {
                $elem['list'] = $this->formatElem($siteCode, $data['middle'][0]);
            }
            if (isset($data['middle'][1])) {
                $elem['info'] = $this->formatElem($siteCode, $data['middle'][1]);
            }
            return $elem;
        }
    }

    public function formatElem($siteCode, $data)
    {
        $result = [];
        $classMethod = "_{$siteCode}ClassElems";
        $fieldMethod = "_{$siteCode}FieldElems";
        $result['classStr'] = $this->$classMethod()[$data[0]];
        $result['fields'] = $this->$fieldMethod()[$data[1]];
        return $result;
    }

    protected function _fiveFieldElems()
    {
        return [
            1 => ['content' => ['method' => 'formatContent']],
            2 => ['content' => ['method' => 'formatContent', 'pointKey' => 'unscramble']],
            3 => ['description' => ['dom' => 'div', 'index' => 0]],
            4 => ['name' => [], 'source_url' => ['method' => 'attr', 'mark' => 'href']],
            5 => ['sort' => ['dom' => 'h2']],
            6 => ['content' => ['method' => 'formatContent', 'dom' => 'div', 'index' => 0, 'pointKey' => 'unscramble']],
            7 => ['description' => ['dom' => '.post--keywords']],
            8 => ['sort' => ['dom' => 'article .block-title']],
            9 => ['content' => ['method' => 'formatContent', 'dom' => '.liebiao-dingbu', 'pointKey' => 'unscramble']],
        ];
    }

    protected function _fiveClassElems()
    {
        return [
            1 => '.section-body .grap',
            2 => '.section-body .grap .nei-img',
            3 => '.main-content',
            4 => '.main-content .listtop',
            5 => '.main-content .layoutSingleColumn li a',
            6 => '.main-content li a',
            7 => '.main-content article',
            8 => '.main-content article h2 a',
            9 => '.shi-jianju a',
            10 => '.main-content article div a',
            11 => '.layoutSingleColumn .paiban li a',
            95 => '.shi-jianju a',
            96 => '.price-info li a',
            97 => '.shi-jianju li a',
            98 => '.main-content a',
            99 => 'a',
        ];
    }

    public function _fiveElems()
    {
        return [
            'daodejingdianping' => ['list' => [8, 4], 'info' => [1, 1]],
            'yijingrumen' => ['list' => [8, 4], 'info' => [1, 1]],
            'yijingcihui' => ['list' => [8, 4], 'info' => [1, 1]],
            'lunyurenwu' => ['list' => [5, 4], 'info' => [1, 1]],
            'ruxuesixiang' => ['list' => [10, 4], 'info' => [1, 1]],
            'ruxuejianjie' => ['list' => [10, 4], 'info' => [1, 1]],
            'ruxue' => ['list' => [7, 8], 'subFilter' => [95, 4], 'info' => [1, 1]],
            'kongzizhuan' => ['list' => [11, 4], 'info' => [1, 1]],
            'yijing2' => ['list' => [7, 5], 'subFilter' => [96, 4], 'info' => [1, 1]],
            'shanhaijing' => ['list' => [7, 8], 'subFilter' => [97, 4], 'middle' => [[8, 4], [3, 9]], 'info' => [1, 1]],
            'sanzijing2' => ['list' => [98, 4], 'info' => [1, 1]],
            'qianziwen2' => ['list' => [98, 4], 'info' => [1, 1]],
            'chuci2' => ['list' => [8, 4], 'middle' => [[8, 4], [3, 6]], 'info' => [1, 1]],
            //'chuci2' => ['list' => [7, 5], 'subFilter' => [9, 4], 'info' => [1, 1]],
            'shijing2' => ['list' => [7, 5], 'subFilter' => [9, 4], 'info' => [1, 1]],
            'xunzi2' => ['list' => [6, 4], 'middle' => [[8, 4], [3, 6]], 'info' => [1, 1]],
            'mozi2' => ['list' => [6, 4], 'middle' => [[8, 4], [3, 6]], 'info' => [1, 1]],
            'sushu' => ['list' => [6, 4], 'middle' => [[8, 4], [3, 3]], 'info' => [1, 1]],
            'caigentan' => ['list' => [6, 4], 'middle' => [[8, 4]], 'info' => [1, 1]],
            'nvxiaojing' => ['list' => [6, 4], 'info' => [1, 1]],
            'zhuangzi2' => ['list' => [7, 5], 'subFilter' => [9, 4], 'middle' => [[8, 4], [3, 3]], 'info' => [1, 1]],
            'lunyu2' => ['list' => [5, 4], 'middle' => [[8, 4], [3, 7]], 'info' => [1, 1]],
            'daodejing2' => ['list' => [11, 4], 'info' => [1, 1]],
            'mengzi2' => ['list' => [10, 4], 'middle' => [[8, 4], [3, 6]], 'info' => [1, 1]],
            'zhongyong2' => ['list' => [6, 4], 'middle' => [[8, 4]], 'info' => [1, 1]],
            'daxuegu' => ['list' => [5, 4], 'middle' => [[8, 4]], 'info' => [1, 1]],
            'sanshiliuji' => ['list' => [7, 5], 'subFilter' => [9, 4], 'info' => [1, 1]],
            'guoyu' => ['list' => [7, 5], 'subFilter' => [99, 4], 'info' => [1, 1]],
            'zuozhuan' => ['list' => [7, 5], 'subFilter' => [99, 4], 'info' => [1, 1]],
            'liji' => ['list' => [5, 4], 'middle' => [[8, 4], [3, 3]], 'info' => [1, 1]],
            //'liji' => ['list' => array_merge($fiveListBase4, ['subFilter' => $subFilter]), 'middle' => ['list' => $fiveListBase2, 'info' => $baseInfoField3], 'info' => $fileInfoElem],
            /*'chuanxilu' => [
                'list' => [
                    'classStr' => '.main-content article', 'record' => false, 
                    'fields' => ['sort' => ['dom' => 'h2'], 'description' => ['dom' => '.shi-jianju', 'index' => 1], 'is_middle' => []],
                    'subFilter' => $subFilter,
                ],
                'middle' => [
                    'list' => ['classStr' => '.main-content article h2 a', 'fields' => $baseListField1]
                ],
                'info' => $fiveInfoElem,
            ],*/
            /*'sunzibingfa' => ['list' => $fiveListBase2, 'info' => $fiveInfoElem],
            'hanfeizi' => ['list' => $fiveListBase1, 'info' => $fiveInfoElem3],
            'xiaojing' => ['list' => $fiveListBase1, 'info' => $fiveInfoElem3],
            'shangshu' => ['list' => $fiveListElem, 'midddle' => $fiveMiddleElem, 'info' => $fiveInfoElem],*/
        ];
    }
}
