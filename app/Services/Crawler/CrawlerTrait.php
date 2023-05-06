<?php

declare(strict_types = 1);

namespace ModuleBench\Services\Crawler;

use Swoolecan\Foundation\Helpers\CommonTool;

trait CrawlerTrait
{
    use CurlTrait;
    use FiveTrait;
    //use \ModuleBench\Services\Crawler\Books\FiveQianDealTrait;

    public function _listDeal($crawler, $commonlist, $filterList = null, $commoninfo = null)
    {
        $datas = [];
        $i = 1;
        //$filterList = is_null($filterList) ? $commonlist->getFilterElem('list') : $filterList;
        $filterList = is_null($filterList) ? $this->getPointElem($commonlist, 'list') : $filterList;
        //print_r($filterList);exit();
        $crawler->filter($filterList['classStr'])->each(function ($node) use ($filterList, $commonlist, $commoninfo, & $i, & $datas) {
            //$record = $filterList['record'] ?? true;
            $dData = [];
            $fields = $filterList['fields'] ?? [];
            foreach ($fields as $field => $domInfo) {
                $dData[$field] = $field == 'is_middle' ? 1 : $this->getPointValue($node, $domInfo);
            }

            $subFilter = $filterList['subFilter'] ?? false;
            if (empty($subFilter)) {
                $datas[] = $this->formatInfoData($dData, $commonlist, $commoninfo, $i);
                $i++;
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

        $check = request()->input('check');
        if ($check) {
            print_r($datas);exit();
        }
        foreach ($datas as $data) {
            if (in_array($data['name'], ['九歌', '九章', '七谏', '九怀', '九辩', '九怀', '九叹'])) {
                continue;
            }
            $this->getModelObj('commoninfo')->createRecord($data, $this->spiderinfo);
        }
        return $datas;
    }

    public function _infoDeal($crawler, $commoninfo)
    {
        $isMiddle = $commoninfo['is_middle'];
        if (empty($isMiddle)) {
            //$filterInfo = $commoninfo->commonlist->getFilterElem('info');
            $filterInfo = $this->getPointElem($commoninfo->commonlist, 'info');
            return $this->_recordInfo($crawler, $commoninfo, $filterInfo);
        }

        $filterElem = $this->getPointElem($commoninfo->commonlist, 'middle');
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
            $node = $this->formatNode($node, $domInfo);
            
            //$sStr = $crawler->filter('.main-content .shi-zhong')->html();//zhuangzhi sanzijing
            $sStr = $crawler->filter('.main-content h2')->html();//zhuangzhi sanzijing
            //$sStr = '';
            $commoninfo->$field = $method ? $this->$method($node, $pointKey, $sStr) : $this->getPointValue($node, $domInfo);
        }
        $check = request()->input('check');
        if ($check) {
            print_r($commoninfo->toArray());
        } else {
            $commoninfo->save();
        }
        return true;
        
    }

    public function formatInfoData($data, $commonlist, $commoninfo, $i)
    {
        $sourceId = basename($data['source_url']);
        $data['source_id'] = str_replace('.html', '', $sourceId);
        $data['name'] = str_replace(['大戴礼记·', '尔雅·'], ['', ''], $data['name']);
        $data['code'] = $i;//CommonTool::getSpellStr($data['name'], '');
        $data['list_id'] = $commonlist['id'];
        $data['info_id'] = $commoninfo ? $commoninfo['id'] : 0;
        $data['spiderinfo_id'] = $commonlist['spiderinfo_id'];
        return $data;
    }

    public function getPointValue($node, $domInfo)
    {
        $node = $this->formatNode($node, $domInfo);

        $method = $domInfo['method'] ?? 'text';
        if ($method == 'attr') {
            $mark = $domInfo['mark'];
            return trim($node->$method($mark));
        }
        if (in_array($method, ['text', 'html'])) {
            return trim($node->$method());
        }
    }

    protected function formatNode($node, $domInfo)
    {
        $dom = $domInfo['dom'] ?? false;
        $node = $dom ? $node->filter($dom) : $node;
        if (isset($domInfo['index'])) {
            $node = $node->eq($domInfo['index']);
        }
        return $node;
    }

    protected function formatContent($node, $key = 'content', $sStr = '')
    {
        $sStr .= $node->html();
        $sStr = strip_tags($sStr);
        $elems = explode("\n", $sStr);
        $datas = [];
        $keys = [
            '【原文】' => 'content', 
            '【按语】' => 'content',
            '【注释】' => 'notes', 
            '【翻译】' => 'vernacular', 
            '【译读】' => 'vernacular', 
            '【译文】' => 'vernacular', 
            '【解释】' => 'vernacular',
            //'【按语】' => 'unscramble',
            '【经典解读】' => 'unscramble',
            '【计名源出】' => 'unscramble',
            //'【实例解读】' => 'unscramble',
            '【解读】' => 'unscramble',
        ];
        $olds = ["'", "''", '〔', '〕', '[', ']', '（', '）', '①','②','③','④','⑤','⑥','⑦','⑧','⑨','⑩','⑪','⑫','⑬','⑭', '⑮', '⑯', '⑰', '⑱', '⑲', '⑳', '㉑', '㉒', '㉓', '㉔', '㉕', '㉖'];
        $news = ['”', '”', '(', ')', '(', ')', '(', ')', '(1)','(2)','(3)','(4)','(5)', '(6)', '(7)', '(8)', '(9)', '(10)', '(11)', '(12)', '(13)', '(14)', '(15)', '(16)', '(17)', '(18)', '(19)', '(20)', '(21)', '(22)', '(23)', '(24)', '(25)', '(26)'];
        foreach ($elems as $value) {
            //$value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
            $value = str_replace(['&nbsp;', "\r", "\n", "\r\n", ' ', '	', '　'], '', trim($value));
            if (empty($value)) {
                continue;
            }
            if (isset($keys[$value])) {
                $key = $keys[$value];
                continue;
            }
            $value = str_replace($olds, $news, $value);
            foreach ($keys as $dKey => $dValue) {
                if (strpos($value, $dKey) !== false) {
                    $preStr = substr($value, 0, strpos($value, $dKey));
                    if (!empty($preStr)) {
                        $datas[$key][] = $preStr;
                    }
                    $key = $dValue;
                    $nextStr = substr($value, strpos($value, $dKey));
                    $value = str_replace($dKey, '', $nextStr);
                    break;
                }
            }
            if (empty($value)) {
                continue;
            }
            $datas[$key][] = $value;
        }
        $check = request()->input('check');
        if ($check) {
            print_r($datas);exit();
        }
        return json_encode($datas);
    }
}
