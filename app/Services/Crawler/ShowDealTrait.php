<?php

declare(strict_types = 1);

namespace ModuleBench\Services\Crawler;

use yii\helpers\FileHelper;

trait ShowDealTrait
{
    protected function _infoGushiwenGushiwen($crawler, $commoninfo)
    {
        //print_r($commoninfo->toArray());exit();
        $aDatas = []; 
		$content = $crawler->filter('.shici-text')->html();
        $contents = explode('<br>', $content);
		$spell = $crawler->filter('.neirong-pinyin')->html();
        $spells = explode('<br>', $spell);
        $s1 = $s2 = [];
        foreach ($spells as $key => $sValue) {
            if ($key % 2 == 0) {
                $s1[] = $sValue;
            } else {
                $s2[] = $sValue;
            }
        }

        $aDatas = [
            'content' => $contents,
            'content1' => $s2,
            'spell' => $s1,
            'other' => [],
        ]; 
		/*$crawler->filter('.shici-ziliao .ziliao')->each(function ($node) use (& $aDatas) {
            $title = trim($node->filter('h2')->text());
            $datas = [];
			$description = $node->filter('p')->each(function ($subNode) use (& $datas) {
                $content = trim($subNode->html());
                $contents = strpos($content, '<br') !== false ? explode('<br>', $content) : $content;
                $datas[] = $contents;
            });
            $aDatas[$title] = $datas;
        });*/
        
		//$content = $crawler->filter('.shici-ziliao')->html();
		$crawler->filter('.shici-ziliao')->each(function ($node) use (& $aDatas) {
            $content = trim($node->html());
            $r = explode('<br>', $content);
            $aDatas['other'][] = $r;
        });
        //print_r($aDatas);exit();

        $result = [
            'name' => $commoninfo['name'],
            'spell' => '',
            'brief' => '',
            'chapters' => [$aDatas],
        ];
        print_r($result['name']);

        $content = "<?php\nreturn ".var_export($result,true).';';
        $file = '/data/htmlwww/laravel-system/vendor/candocker/website/migrations/chuci/' . $commoninfo['id'] . '.php';
        file_put_contents($file, $content);
        return true;
    }

    //protected function _infoGuoxueGuoxue($crawler, $commoninfo)
    protected function _infoGxbaodianGxbaodian($crawler, $commoninfo)
    {
        //print_r($commoninfo->toArray());exit();
        $spiderinfo = $commoninfo->spiderinfoData;

        $aDatas = []; 
		//$crawler->filter('.lacontent p')->each(function ($subNode) use (& $aDatas) {
		$crawler->filter('.contentBox p')->each(function ($subNode) use (& $aDatas) {
            $aDatas[] = trim($subNode->text());
		});
        $i = 1;
        $result = [];
        $key = 'content';
        foreach ($aDatas as $index => $data) {
            $key = in_array($key, ['content']) && strpos($data, '关键词：') !== false ? 'vernacular' : $key;
            //$key = $key == 'vernacular' && strpos($data, '赏析') !== false ? 'unscramble' : $key;
            $key = $key == 'vernacular' && strpos($data, '（1）') !== false ? 'note' : $key;
            //var_dump($key);
            if (in_array($key, ['content'])) {
                //$key = $index % 2 == 0 ? 'spell' : 'content';
            }
            //$data = trim($data, '　');
            $result[$key][] = trim($data);
        }
        //print_r($result);exit();
        /*$format = [
            'name' => $commoninfo['name'],
            'brief' => '',
            'chapters' => [array_merge(['name' => $commoninfo['name']], $result)],
        ];*/
        $commoninfo->code_ext = json_encode($result);
        return true;
        //print_r($format);exit();
        $content = "<?php\nreturn ".var_export($format,true).';';
        $file = '/data/htmlwww/laravel-system/vendor/candocker/website/migrations/' . $commoninfo['extfield'] . '/' . $commoninfo['code_ext'] . '.php';

        file_put_contents($file, $content);
		return true;
    }

    protected function _infoGuoxueGuoxueold($crawler, $commoninfo)
    {
        //print_r($commoninfo->toArray());exit();
        $spiderinfo = $commoninfo->spiderinfoData;

        $aDatas = []; 
		$crawler->filter('.lacontent p')->each(function ($subNode) use (& $aDatas) {
            $aDatas[] = $subNode->text();
		});
        $i = 1;
        $result = [];
        $key = 'spell';
        foreach ($aDatas as $index => $data) {
            $key = in_array($key, ['spell', 'content']) && strpos($data, '关键词：') !== false ? 'vernacular' : $key;
            $key = $key == 'vernacular' && strpos($data, '赏析') !== false ? 'unscramble' : $key;
            $key = $key == 'unscramble' && strpos($data, '⑴') !== false ? 'note' : $key;
            //var_dump($key);
            if (in_array($key, ['spell', 'content'])) {
                $key = $index % 2 == 0 ? 'spell' : 'content';
            }
            $result[$key][] = $data;
        }

        $redis = $this->getServiceObj('passport-redis');
        $key = 'spider-info-index';
        $value = $redis->get($key) ? $redis->get($key) : 1;
        $redis->set($key, $value + 1);
        
        $format = [
            'name' => $commoninfo['code_ext'],
            'spell' => '',
            'brief' => '',
            'chapters' => [array_merge(['name' => $commoninfo['code_ext']], $result)],
        ];
        echo $value . '=' . $commoninfo['code_ext'] . '-<br />';
        $content = "<?php\nreturn ".var_export($format,true).';';
        $fileName = $value < 10 ? '0' . $value . '.php' : $value . '.php';
        $file = '/data/htmlwww/laravel-system/vendor/candocker/website/migrations/shijing/' . $fileName;

        file_put_contents($file, $content);
        print_R($result);exit();
		return true;
    }
}
