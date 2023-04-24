<?php

declare(strict_types = 1);

namespace ModuleBench\Services\Crawler;

trait CrawlerTrait
{
    use CurlTrait;
    //use \ModuleBench\Services\Crawler\Books\FiveQianDealTrait;

    public function _listDeal($crawler, $commonlist, $filterList = null, $commoninfo = null)
    {
        $datas = [];
        $i = 1;
        $filterList = is_null($filterList) ? $commonlist->getFilterElem('list') : $filterList;
        $crawler->filter($filterList['classStr'])->each(function ($node) use ($filterList, $commonlist, $commoninfo, & $i, & $datas) {
            $record = $filterList['record'] ?? true;
            $dData = [];
            $fields = $filterList['fields'] ?? [];
            foreach ($fields as $field => $domInfo) {
                $dData[$field] = $field == 'is_middle' ? 1 : $this->getPointValue($node, $domInfo);
            }
            if ($record) {
                $datas[] = $this->formatInfoData($dData, $commonlist, $commoninfo, $i);
                $i++;
            }

            $subFilter = $filterList['subFilter'] ?? false;
            if (empty($subFilter)) {
                return $datas;
            }
            $node->filter($subFilter['classStr'])->each(function ($subNode) use ($subFilter, $dData, $commonlist, $commoninfo, & $datas, & $i) {
                $subData = $dData;
                $fields = $subFilter['fields'] ?? [];
                foreach ($fields as $field => $domInfo) {
                    $subData[$field] = $this->getPointValue($subNode, $domInfo);
                }
                $datas[] = $this->formatInfoData($subData, $commonlist, $commoninfo, $i);

                $i++;
            });
        });

        //print_r($datas);exit();
        foreach ($datas as $data) {
            $this->getModelObj('commoninfo')->createRecord($data, $this->spiderinfo);
        }
        return $datas;
    }

    public function _infoDeal($crawler, $commoninfo)
    {
        $isMiddle = $commoninfo['is_middle'];
        if (empty($isMiddle)) {
            $filterInfo = $commoninfo->commonlist->getFilterElem('info');
            return $this->_recordInfo($crawler, $commoninfo, $filterInfo);
        }

        $filterElem = $commoninfo->commonlist->getFilterElem('middle');
        if (isset($filterElem['info'])) {
            $this->_recordInfo($crawler, $commoninfo, $filterElem['info']);
        }
        return $this->_listDeal($crawler, $commoninfo->commonlist, $filterElem['list'], $commoninfo);
    }

    protected function _recordInfo($crawler, $commoninfo, $filterInfo)
    {
        $classStr = $filterInfo['classStr'];
        $fields = $filterInfo['fields'] ?? [];
        $node = $crawler->filter($classStr);
        foreach ($fields as $field => $domInfo) {
            $method = $domInfo['method'] ?? false;
            $pointKey = $domInfo['pointKey'] ?? 'content';
            $commoninfo->$field = $method ? $this->$method($node, $pointKey) : $this->getPointVaie($node, $domInfo);
        }
        $commoninfo->save();
        return true;
        
    }

    public function formatInfoData($data, $commonlist, $commoninfo, $i)
    {
        $sourceId = basename($data['source_url']);
        $data['source_id'] = str_replace('.html', '', $sourceId);
        $data['name'] = str_replace(['大戴礼记·', '尔雅·'], ['', ''], $data['name']);
        $data['code'] = $i;
        $data['list_id'] = $commonlist['id'];
        $data['info_id'] = $commoninfo ? $commoninfo['id'] : 0;
        $data['spiderinfo_id'] = $commonlist['spiderinfo_id'];
        return $data;
    }

    public function getPointValue($node, $domInfo)
    {
        $dom = $domInfo['dom'] ?? false;
        $method = $domInfo['method'] ?? 'text';
        $node = $dom ? $node->filter($dom) : $node;

        if ($method == 'attr') {
            $mark = $domInfo['mark'];
            return trim($node->$method($mark));
        }
        if (in_array($method, ['text', 'html'])) {
            return trim($node->$method());
        }
    }

    protected function formatContent($node, $key = 'content')
    {
        $elems = $node->children();
        $datas = [];
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
        foreach ($elems as $elem) {
            $value = trim($elem->nodeValue);
            $value = str_replace([' '], [''], $value);
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
        //print_r($datas);exit();
        return json_encode($datas);
    }
}
