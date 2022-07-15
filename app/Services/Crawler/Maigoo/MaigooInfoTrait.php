<?php

declare(strict_types = 1);

namespace ModuleBench\Services\Crawler\Maigoo;

use Overtrue\Pinyin\Pinyin;

Trait MaigooInfoTrait
{
    protected function _infoMaigooHuman($crawler, $commoninfo)
    {
        //print_r($commoninfo->toArray());exit();
        $spiderinfo = $commoninfo->spiderinfoData;
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, $spiderinfo['info_table']);
        $info = $targetModel->getInfo($commoninfo->target_id);
        //$info->content = $this->getCrawlerElem($crawler, '.artcontent', '', 'html');
        $info->content = $crawler->filter('.artcontent .artcontent');
        if (count($crawler->filter('.artcontent .artcontent')) < 1) {
            $content = $crawler->filter('.articlecont');//
            echo count($content);
            if (count($content) < 1) {
                return false;
                echo $commoninfo->_getFile();
                
                echo 'sss';
                print_r($commoninfo->toArray());exit();
            }
            $info->content = $content->html();
        } else {
            $info->content = $crawler->filter('.artcontent .artcontent')->html();
        }
        //$info->tag = $this->getCrawlerTag($crawler, '.tablecell .dhidden');
        //print_r($info->toArray());exit();

        $info->update(false, ['content']);
        return true;
    }

    protected function _infoMaigooStore($crawler, $commoninfo)
    {
        //print_r($commoninfo->toArray());exit();
        $spiderinfo = $commoninfo->spiderinfoData;
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, $spiderinfo['info_table']);
        $info = $targetModel->getInfo($commoninfo->target_id);
        $info->description = $this->getCrawlerElem($crawler, '.describe', '', 'text');
        //print_r($info);exit();
        $info->tag = $this->getCrawlerTag($crawler, '.tablecell .dhidden');
        //print_r($info->toArray());exit();

        $info->update(false, ['description', 'tag']);
        return true;
    }

    protected function _infoMaigooWebsite($crawler, $commoninfo)
    {
        //print_r($commoninfo->toArray());exit();
        $spiderinfo = $commoninfo->spiderinfoData;
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, $spiderinfo['info_table']);
        $info = $targetModel->getInfo($commoninfo->target_id);
        $info->brief = trim($crawler->filter('.describe')->text());
        //print_r($info);exit();
        $info->note = $this->getCrawlerTag($crawler, '.descbox ul li');
        $info->update(false, ['note', 'brief']);
        return true;
    }

    protected function _infoMaigooBrandtop($crawler, $commoninfo)
    {
        print_r($commoninfo->toArray());exit();
        $spiderinfo = $commoninfo->spiderinfoData;
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, $spiderinfo['info_table']);
        $info = $targetModel->getInfo($commoninfo->target_id);

        $i = 0;
        $crawler->filter('.pagenav .navcont a')->each(function ($subNode) use (& $aDatas, $commoninfo, $info, $spiderinfo, & $i) {
            $title = trim($subNode->text());
            $url = trim($subNode->attr('href'));
            $titles = [
                '产品' => 'product',
                '聚焦' => 'article',
                '网店' => 'website',
                //'企业' => 'description',
                '网点' => 'store',
                '招商' => 'league',
            ];

            echo $title . '--' . $url . '<br />';
            $currentTitle = $titles[$title] ?? '';
            if (empty($currentTitle)) {
                echo $title . '-=-=-=<br />';
                return ;
            }
            $listData = ['page' => 1, 'name' => $title, 'code' => $commoninfo['source_id'], 'url' => $url];
            //print_R($listData);
            $spiderIds = [
                //'human' => 39,
                'article' => 32,
                'website' => 41,
                'league' => 43,
                'store' => 31,
                //'knowledge' => 40,
                'product' => 30,
            ];
            $spider = $this->_getPointModelData('spiderinfo', $spiderIds[$currentTitle]);
		    $this->_writeList($listData['url'], $listData['page'], $listData, $spider);
            $i++;
        });
        if (empty($i)) {
            echo 'no-------------' . $commoninfo['id'] . '==' . "<a href='{$commoninfo['source_url']}' target='_blank'>{$commoninfo['id']}</a><br />";
        }
        //exit();
        return true;
    }

    protected function _infoMaigooSubject($crawler, $commoninfo)
    {
        print_r($commoninfo->toArray());exit();
        $spiderinfo = $commoninfo->spiderinfoData;
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, $spiderinfo['info_table']);
        $info = $targetModel->getInfo($commoninfo->target_id);

        $i = 0;
        /*$crawler->filter('.pagenav .navcont a')->each(function ($subNode) use (& $aDatas, $commoninfo, $info, $spiderinfo, & $i) {
            $title = trim($subNode->text());
            $url = trim($subNode->attr('href'));
            $titles = [
                '知识' => 'knowledge',
                '聚焦' => 'article',
                '名人' => 'human',
            ];

            //echo $title . '--' . $url . '<br />';
            $currentTitle = $titles[$title] ?? '';
            if (empty($currentTitle)) {
                return ;
            }
            $listData = ['page' => 1, 'name' => $title, 'code' => $commoninfo['source_id'], 'url' => $url];
            //print_R($listData);
            $spiderIds = [
                'human' => 39,
                'article' => 32,
                'knowledge' => 40,
            ];
            $spider = $this->_getPointModelData('spiderinfo', $spiderIds[$currentTitle]);
		    $this->_writeList($listData['url'], $listData['page'], $listData, $spider);
            $i++;
        });
        if (empty($i)) {
            echo 'no-------------' . $commoninfo['id'] . '==' . "<a href='{$commoninfo['source_url']}' target='_blank'>{$commoninfo['id']}</a><br />";
        }*/
        return true;
    }
    
    protected function _infoMaigooKnowledge($crawler, $commoninfo)
    {
        //print_r($commoninfo->toArray());exit();
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, $this->info_table);
        $info = $targetModel->getInfo($commoninfo->target_id);
        $contentDom = $crawler->filter('.only-cont');
        if (count($contentDom) >= 1) {
		    $content = $crawler->filter('.only-cont')->html();
        } else {
            $content = $crawler->filter('.articlecont');//
            echo count($content);
            if (count($content) < 1) {
                return false;
                echo $commoninfo->_getFile();
                
                echo 'sss';
                print_r($commoninfo->toArray());exit();
            }
            $content = $content->html();
            $info->description = $crawler->filter('.description')->text();
        }
        $spiderinfo = $commoninfo->spiderinfoData;

        $aDatas = []; 
		$crawler->filter('.only-cont img')->each(function ($subNode) use (& $aDatas, $commoninfo, $info, $spiderinfo) {
            $sUrl = trim($subNode->attr('src'));
			$aData = [
                'source_id' => $commoninfo['source_id'],
				'source_url' => $sUrl,
                'info_table' => $spiderinfo['info_table'],
                'info_field' => 'content',
                'info_id' => $info['id'],
				'name' => $info['name'],
			];
			$aDatas[] = $aData;
		});
        //echo $content;
        //print_R($aDatas);exit();
		$info->content = $this->_dealContent($content, $aDatas);
        //echo $info->content;exit();
		$info->update(false, ['content']);
    }
    
    protected function _infoMaigooArticle($crawler, $commoninfo)
    {
        //print_r($commoninfo->toArray());exit();
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, $this->info_table);
        $info = $targetModel->getInfo($commoninfo->target_id);
		$content = $crawler->filter('.only-cont')->html();
        $spiderinfo = $commoninfo->spiderinfoData;

        $aDatas = []; 
		$crawler->filter('.only-cont img')->each(function ($subNode) use (& $aDatas, $commoninfo, $info, $spiderinfo) {
            $sUrl = trim($subNode->attr('src'));
			$aData = [
                'source_id' => $commoninfo['source_id'],
				'source_url' => $sUrl,
                'info_table' => $spiderinfo['info_table'],
                'info_field' => 'content',
                'info_id' => $info['id'],
				'name' => $info['name'],
			];
			$aDatas[] = $aData;
		});
        //echo $content;
        //print_R($aDatas);exit();
		$info->content = $this->_dealContent($content, $aDatas);
        //echo $info->content;exit();
		$info->update(false, ['content']);
    }

    protected function _infoMaigooBrand($crawler, $commoninfo)
    {
        print_r($commoninfo->toArray());exit();
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, $this->info_table);
        $info = $targetModel->getInfo($commoninfo->target_id);
		$info['name'] = $crawler->filter('.brandname span')->text();
		$info['company'] = $crawler->filter('.brandname .cname')->text();
		//$info->update(false);
		//return ;

        $info->description = trim($crawler->filter('.desc')->text());
        //$info->update(false);

        $i = 0;
        //`id`, `spiderinfo_id`, `name`, `code`, `code_ext`, `description`, `spider_num`, `spider_sourcenum`, `created_at`, `updated_at`, `source_site`, `source_url`, `source_page`, `status`

        //$mallUrl = 
        $crawler->filter('.navcont a')->each(function ($node) use ($commoninfo) {
            $name = trim($node->text());
            $baseUrl = $node->attr('href');
            $commonlist = $this->getPointModel('commonlist');
            $exist = $commonlist->getInfo($baseUrl, 'source_url');
            if ($exist) {
                return ;
            }
            $params = [
                'code' => $commoninfo->source_id,
                'source_site' => 'maigoo',
                'source_page' => 1,
                'source_url' => $baseUrl,
            ];
            if ($name == '产品') {
                $params['spiderinfo_id'] = 30;
                $commonlist->addInfo($params);
                echo $node->attr('href');
            }
            if ($name == '网店') {
                $params['spiderinfo_id'] = 31;
                $commonlist->addInfo($params);
            }
        });
        $crawler->filter('.c666 li')->each(function ($node) use ($commoninfo, $info, & $i) {
            if ($i >= 5) {
                return ;
            }
            $str = trim($node->text());
            echo $str . '<br />';
            $fields = ['hotline', 'website', 'builder', 'address_first', 'build_time'];
            $field = $fields[$i];
            $info->$field = $str;
            $i++;
        });
		//$info->update(false);

        $src = $crawler->filter('#brandlogo img')->attr('src');

        if (strpos($src, '?')) {
            $src = substr($src, 0, strpos($src, '?'));
        }
        if (strpos($src, '!')) {
            $src = substr($src, 0, strpos($src, '!'));
        }
        $aData = [
            'info_table' => 'brand',
            'info_table' => 'logo',
            'info_id' => $info['id'],
            'spiderinfo_id' => $this->id,
            'source_site' => $this->site_code,
            'source_id' => $commoninfo['source_id'],
            'created_at' => time(),
			'name' => $info['name'],
			'filename' => $info['name'],
			'source_url' => $src,
			'description' => $info['name'],
        ];
		//print_r($aData);exit();
        $aData['source_url'] = $src;
        $aData['info_field'] = 'logo';
        $this->getPointModel('attachment-bench')->addInfoCheck($aData, ['info_id', 'info_table', 'info_field', 'source_url']);
    }
}
