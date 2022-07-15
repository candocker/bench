<?php

declare(strict_types = 1);

namespace ModuleBench\Services\Crawler;
        
trait TmpCrawlerOpe
{
	public function filter()
	{
        $crawler = new Crawler();
        $crawler->addContent($this->getContent($file));

        $crawler->filter('.wrap .merger_box')->each(function ($node) use (& $i, & $data) {
			$nameBase = $node->filter('h2 a');
			if (count($nameBase) < 1) {
				return ;
			}

			$cName = $node->text();
            $name = $node->filter('div h3')->text();
            $pCode = Pinyin::letter($cName, ['delimiter' => '', 'accent' => false]);
            $pCode = Pinyin::trans($cName, ['delimiter' => '', 'accent' => false]);

		    $thumb = $node->filter('img');
		    $thumb = count($thumb) > 0 ? $thumb->attr('src') : '';

            $created_at = $attrs->eq(0)->text();//nodeValue;
            $created_at = strtotime($created_at);

            $author = $attrs->eq(1)->text();//nodeValue;
            $content = trim($crawler->filter('#ctrlfscont')->html());
            $content = preg_replace("'<script(.*?)<\/script>'is", '', $content);
            //echo $content;exit();
        });


        $tags = $node->filter('a');
        foreach ($tags as $key => $attr) {
            $value = trim($attr->nodeValue);
            $data[$name][$subName][] = $value;
        }
        $tagInfos = $node->filter('.tags')->eq(0)->filter('em');
	}
	protected function dealContent($crawler, $info, $content)
	{
        $pictures = [];
        $pattern = '@<p>.*<img.*src=.*"(?P<img>.*)".*>@Us';
        preg_match_all($pattern, $content, $infos);
        $pictures = $infos['img'];
        if (empty($pictures)) {
            $pattern = '@{"type":"image","content":"(?P<img>.*)"}@Us';
            preg_match_all($pattern, $content, $infos);
            $pictures = $infos['img'];
        }
		$pictures = array_filter($pictures);
        if (empty($pictures)) {
            echo $info['source_id'] . 'nopicture<br />';
        }
		echo $info['id'] . '--p--' . count($pictures) . '<br />';
        $this->_addPicture($pictures, 'picture', $info);
		return count($pictures);
    }

    public function _singleZhubjTmcategory($crawler)
    {
        $str = '';
		$cSql = 'INSERT INTO `wp_tmcategory` (`name`, `code`, `description`) VALUES ';
		$sql = '';
		$i = 1;
		$crawler->filter('.sort-category .industry-list')->each(function ($node) use (& $sql) {
			$iName = $node->filter('.industry-name');
			$name = count($iName) > 0 ? trim($iName->text()) : '';
			echo $name . '<br />';
			$node->filter('..rerelation a');
			//echo 'sss';
			//echo $node->text() . '<br />';
		});
		echo $sql;exit();
        /*$crawler->filter('.sort-category a')->each(function ($node) use (& $cSql, & $i) {
			$code = $node->filter('span');
			$code = count($code) > 0 ? $code->text() : '';
			$code = str_replace(['第', '类'], ['', ''], $code);
			$name = $node->filter('p');
			$name = count($name) > 0 ? $name->text() : '';
			if (!empty($name)) {
			    $cSql .= "('{$name}', '{$code}', '{$name}'),<br />\n";
				$i++;
			}
		});
		echo $cSql;exit();*/
    }
}

namespace bench\spider\models\crawler\office;

use Overtrue\Pinyin\Pinyin;

Trait ProomTrait
{
	protected function _infoOfficePinstitution($crawler, $commoninfo)
	{
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, $this->info_table);
        $info = $targetModel->getInfo($commoninfo->target_id);

		$info->goods_field = $crawler->filter('.institute-shop-text1')->text();
		$info->price = $crawler->filter('.institute-shop-money span')->text();
		$info->city = $crawler->filter('.coordinate-div span')->eq(1)->text();
		$data = $info->toArray();
		$info->update(false);
	}

	protected function _infoOfficePdesigner($crawler, $commoninfo)
	{
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, $this->info_table);
        $info = $targetModel->getInfo($commoninfo->target_id);

		$info->goods_field = $crawler->filter('.designer-shop-text1')->text();
		$elems = $crawler->filter('.designer-shop-type span');
		$i = 1;
		foreach ($elems as $elem) {
			$value = $elem->nodeValue;
			if ($i == 1) {
				$info->title = $value;
			} else {
				$info->goods_style = $value;
			}
			$i++;
		}
		$info->price = $crawler->filter('.designer-shop-money')->text();
		$info->city = $crawler->filter('.coordinate-div span')->text();
		$info->design_concept = $crawler->filter('.designer-card-depict1')->text();
		$data = $info->toArray();
		$info->update(false);
		return true;
	}

	protected function _infoOfficePrealcase($crawler, $commoninfo)
	{
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, $this->info_table);
        $info = $targetModel->getInfo($commoninfo->target_id);

		$elemStr = $crawler->filter('.case-top-base')->text();
		list($eSpace, $eSubSpace, $eStyle, $eArea) = explode('/', $elemStr);
		$info->space = strval($eSpace);
		$info->space_sub = strval($eSubSpace);
		$info->style = strval($eStyle);
		$info->area = strval($eArea);
		$info->update(false);

		return ;
		print_r($elems);exit();

		$iUrl = $crawler->filter('.anli-desCard-img-div a');
		if (count($iUrl) < 1) {
			$iUrl = '';
		} else {
			$iUrl = $iUrl->attr('href');
		}
		$iId = basename($iUrl);
		$iId = str_replace('.html', '', $iId);
		$info->extfield = $iId;

		$tags = $crawler->filter('.anli-sort-type span');
		$tagStr = '';
		foreach ($tags as $tag) {
			$value = trim($tag->nodeValue);
			$tagStr .= $value == '全选' ? '' : $value . ',';
		}
		$info->tag = $tagStr;

		$descs = $crawler->filter('.mete-depict-div .alli-depict1');
		$info->demand = count($descs) >= 1 ? $descs->eq(0)->text() : '';
		$info->design_concept = count($descs) > 1 ? $descs->eq(1)->text() : '';

        $aData = [
            'info_table' => 'realcase',
            'info_id' => $info['id'],
            'spiderinfo_id' => $this->id,
            'source_site' => $this->site_code,
            'source_id' => $commoninfo['source_id'],
            'created_at' => time(),
        ];

		$crawler->filter('.mete-div img')->each(function ($tmp) use ($aData) {
			$src = $tmp->attr('src');
			if (strpos($src, '?')) {
				$src = substr($src, 0, strpos($src, '?'));
			}
			if (strpos($src, '!')) {
				$src = substr($src, 0, strpos($src, '!'));
			}
			$name = $tmp->attr('alt');
			$aData['name'] = $name;
			$aData['filename'] = $name;
			$aData['description'] = $name;
			$aData['source_url'] = $src;
			$aData['info_field'] = 'picture_working';
            $this->getPointModel('attachment-bench')->addInfoCheck($aData, ['info_id', 'info_table', 'info_field', 'source_url']);
		});
		$crawler->filter('.draw-item')->each(function ($node) use ($info, $aData) {
			$src = $node->filter('img')->attr('src');
			if (strpos($src, '?')) {
				$src = substr($src, 0, strpos($src, '?'));
			}
			if (strpos($src, '!')) {
				$src = substr($src, 0, strpos($src, '!'));
			}
			$name = $node->filter('.draw-title')->text();
			$description = $node->filter('.draw-text')->text();
			$aData['name'] = $name;
			$aData['filename'] = $name;
			$aData['description'] = $description;
			$aData['source_url'] = $src;
			$aData['info_field'] = 'picture_design';
            $this->getPointModel('attachment-bench')->addInfoCheck($aData, ['info_id', 'info_table', 'info_field', 'source_url']);
		});
		$crawler->filter('.swiper-container img')->each(function ($node) use ($info, $aData) {
			$src = $node->attr('src');
			if (strpos($src, '?')) {
				$src = substr($src, 0, strpos($src, '?'));
			}
			if (strpos($src, '!')) {
				$src = substr($src, 0, strpos($src, '!'));
			}
			$name = $node->attr('alt');
			$aData['name'] = $name;
			$aData['filename'] = $name;
			$aData['description'] = $name;
			$aData['source_url'] = $src;
			$aData['info_field'] = 'picture_deliver';
            $this->getPointModel('attachment-bench')->addInfoCheck($aData, ['info_id', 'info_table', 'info_field', 'source_url']);
		});
		$info->update(false);
		return true;

	    exit();	
	}

	protected function _infoOfficePsample($crawler, $commoninfo)
	{
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, $this->info_table);
        $info = $targetModel->getInfo($commoninfo->target_id);

		$iUrl = $crawler->filter('.gallery-left-con a');
		if (count($iUrl) < 1) {
			$iUrl = '';
		} else {
			$iUrl = $iUrl->attr('href');
		}
		$iId = basename($iUrl);
		$iId = str_replace('.html', '', $iId);
		$info->extfield = $iId;

		$tagStr = '';
		$tags = $crawler->filter('.gallery-left-tag-div a');
		foreach ($tags as $key => $attr) {
			$tagStr .= trim($attr->nodeValue) . ',';
		}
		$info->tag = $tagStr;

		$elems = $crawler->filter('.gallery-detail-center .detail-base-text');
		$eValues = [];
		$i = 1;
		foreach ($elems as $key => $elem) {
			$eValues[$i] = trim($elem->nodeValue);
			$i++;
		}
		$info->area = isset($eValues[2]) ? $eValues[2] : '';
		$info->partion = isset($eValues[3]) ? $eValues[3] : '';
		$info->price = isset($eValues[4]) ? $eValues[4] : '';
		if (isset($eValues[5])) {
			$info->created_at = strtotime($eValues[5]);
			$info->updated_at = strtotime($eValues[5]);
		}
		$info->update(false);

        $pCode = $crawler->filter('.pagespic')->attr('data-img');
		$commoninfo->code_ext = $pCode;
		$commoninfo->status = 100;
		$commoninfo->update(false, ['code_ext', 'status']);
	}

	protected function _infoOfficeParticle($crawler, $commoninfo)
	{
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, $this->info_table);
        $info = $targetModel->getInfo($commoninfo->target_id);

		$time = $crawler->filter('.theory-detail-from .ml10')->text();
		$time = empty($time) ? 0 : strtotime($time);
		$info->created_at = $time;
		$info->updated_at = $time;
		$info->content = $crawler->filter('.theory-detail-LCenter')->html();
		$info->update(false);
		return true;
	}

	public function _listOfficePinstitution($crawler, $commonlist)
	{
		$datas = [];
		$i = 1;
        $crawler->filter('.institute-list-con .institute-list-item')->each(function ($node) use (& $datas, $commonlist, & $i) {
            $thumb = $node->filter('.institute-list-pic-div img')->attr('src');
            $logo = $node->filter('.institute-list-logo-div img')->attr('src');
            $name = $node->filter('.institute-list-logo-div img')->attr('alt');

            $sourceUrl = $node->filter('a')->attr('href');
            $sourceId = str_replace('.html', '', basename($sourceUrl));
			$datas[] = [
				'source_url' => $sourceUrl,
				'name' => $name,
				//'sort' => $commonlist['code'],
				'source_id' => $sourceId,
				'thumb' => $thumb,
				'logo' => $logo,
			];
		});
		//print_r($datas);exit();
		return $datas;
	}

	public function _listOfficePdesigner($crawler, $commonlist)
	{
		$datas = [];
		$i = 1;
        $crawler->filter('.designer-list-wrap .designer-list-item')->each(function ($node) use (& $datas, $commonlist, & $i) {
            $imgObj = $node->filter('a img');
            $img = $imgObj->attr('src');
			$name = $imgObj->attr('alt');

            $sourceUrl = $node->filter('a')->attr('href');
            $sourceId = str_replace('.html', '', basename($sourceUrl));
			$datas[] = [
				'source_url' => $sourceUrl,
				'name' => $name,
				//'sort' => $commonlist['code'],
				'source_id' => $sourceId,
				'thumb' => $img,
			];
		});
		//print_r($datas);exit();
		return $datas;
	}

	public function _listOfficePrealcase($crawler, $commonlist)
	{
		$datas = [];
		$i = 1;
        $crawler->filter('.case-list-content .case-list-item')->each(function ($node) use (& $datas, $commonlist, & $i) {
            $imgObj = $node->filter('a img');
            $img = $imgObj->attr('src');
			$name = $imgObj->attr('alt');

            $sourceUrl = $node->filter('a')->attr('href');
            $sourceId = str_replace('.html', '', basename($sourceUrl));
			$datas[] = [
				'source_url' => $sourceUrl,
				'name' => $name,
				//'sort' => $commonlist['code'],
				'source_id' => $sourceId,
				'thumb' => $img,
			];
		});
		//print_r($datas);exit();
		return $datas;
	}

	public function _listOfficePsample($crawler, $commonlist)
	{
		$datas = [];
		$i = 1;
        $crawler->filter('.gallery-list-con .gallery-list-item')->each(function ($node) use (& $datas, $commonlist, & $i) {
            $imgObj = $node->filter('a img');
            $img = $imgObj->attr('src');

			$baseElem = $node->filter('a');
            $sourceUrl = $node->filter('a')->first()->attr('href');
            $name = $baseElem->filter('.gallery-list-text')->text();

            $sourceId = str_replace('.html', '', basename($sourceUrl));
			$datas[] = [
				'source_url' => $sourceUrl,
				'name' => $name,
				'sort' => $commonlist['code'],
				'source_id' => $sourceId,
				'thumb' => $img,
			];
		});
		//print_r($datas);exit();
		return $datas;
	}

	public function _listOfficeParticle($crawler, $commonlist)
	{
		$datas = [];
		$i = 1;
        $crawler->filter('.theory-list-left-con .theory-list-item')->each(function ($node) use (& $datas, $commonlist, & $i) {
            $imgObj = $node->filter('.theory-list-img-div img');
			$num = count($imgObj);
			if ($num < 1) {
				return ;
			}
			//echo count($imgObj);exit();
            $img = $imgObj->attr('src');

			$baseElem = $node->filter('.theory-list-info');
            $sourceUrl = $node->filter('a')->first()->attr('href');
            $name = $baseElem->filter('.theory-list-name')->text();
            $title = $baseElem->filter('.theory-list-depict')->text();

			$tags = $node->filter('.theory-list-type a');
			$tagStr = '';
			foreach ($tags as $key => $attr) {
				$tagStr .= trim($attr->nodeValue) . ',';
			}
			//echo $tagStr . '==' . $title . '==' . $name . '==' . $sourceUrl . '==' . $img;exit();

            $sourceId = str_replace('.html', '', basename($sourceUrl));
			$datas[] = [
				'source_url' => $sourceUrl,
				'name' => $name,
				'code' => $sourceId,
				'content' => '',
				'tag' => $tagStr,
				'description' => $title,
				'source_id' => $sourceId,
				'thumb' => $img,
			];
		});
		//print_r($datas);exit();
		return $datas;
	}

    public function _recordOfficeParticle()
	{
		$elems = [
			'daka' => ['page' => 16], 
			'gongzhuang' => ['page' => 50], 
			'qianyan' => ['page' => 50], 
			'hangye' => ['page' => 50], 
		];
		foreach ($elems as $elem => $info) {
			$url = "https://www.wenes.cn/{$elem}/{{PAGE}}.html";
		    $this->_writeList($url, $info['page'], $elem);
		}
		return ;
	}

    public function _recordOfficePsample()
	{
		$elems = [
			'bangong' => ['code' => 42, 'page' => 82], 
			'canyin' => ['code' => 43, 'page' => 11], 
			'shangye' => ['code' => 44, 'page' => 19], 
			'jiudian' => ['code' => 45, 'page' => 4], 
			'jiaoyu' => ['code' => 46, 'page' => 7], 
			'huisuo' => ['code' => 47, 'page' => 5], 
			'shuidian' => ['code' => 48, 'page' => 5], 
			'shigong' => ['code' => 49, 'page' => 37], 
		];
		foreach ($elems as $elem => $info) {
			$url = "https://tuku.wenes.cn/{$elem}/{$info['code']}-0-0-0-id-{{PAGE}}.html";
		    $this->_writeList($url, $info['page'], $elem);
		}
		return ;
	}

    public function _recordOfficePrealcase()
	{
		$url = 'https://www.wenes.cn/case/11-0-0-0-0-id-desc-{{PAGE}}.html';
		return $this->_writeList($url, 70);
	}

    public function _recordOfficePdesigner()
	{
		$url = 'https://www.wenes.cn/shejishi/12-0-0-count-desc-{{PAGE}}.html';
		return $this->_writeList($url, 68);
	}

    public function _recordOfficePinstitution()
	{
		$url = 'https://www.wenes.cn/shejiyuan/{{PAGE}}.html';
		//return $this->_writeList($url, 4);
	}

    public function _singleOfficePspace($crawler)
    {
        $str = '';
		$sql = 'INSERT INTO `wp_space` (`name`, `code`, `description`, `parent_code`) VALUES ';
		$i = 1;
		$crawler->filter('.home-side-main li')->each(function ($node) use (& $sql) {
			$iName = $node->filter('a');
			$class = $iName->attr('class');
			if ($class == 'home-side-sort1') {
			$name = count($iName) > 0 ? trim($iName->text()) : '';
			$description = $iName->attr('href');
            $code = Pinyin::letter($name, ['delimiter' => '', 'accent' => false]);
			$sql .= "('{$name}', '{$code}', '{$description}', ''),<br />\n";
			} else {
				return ;
			}
			//echo $name . '-' . $description . '-' . $code;exit();
			$node->filter('.home-side-tab-item li')->each(function ($subNode) use (& $sql, $code) {
			    $tmp = $subNode->filter('a');
			    $subName = count($tmp) > 0 ? trim($tmp->text()) : '';
			    $subDescription = $tmp->attr('href');
                $subCode = Pinyin::letter($subName, ['delimiter' => '', 'accent' => false]);

			    $sql .= "('{$subName}', '{$subCode}', '{$subDescription}', '{$code}'),<br />\n";

			});
				//echo $sql;exit();
		});
		echo $sql;exit();
    }
}

namespace bench\spider\models\crawler\jmw;

use Overtrue\Pinyin\Pinyin;

Trait JmwTrait
{
    public function _singleJmwCategory($crawler)
    {
        $str = '';
		$cSql = 'INSERT INTO `wp_category` (`name`, `code`, `description`, `parent_code`) VALUES ';
        $category = [];
		$i = 0;
        $crawler->filter('#dorpdown_layer1 dl')->each(function ($node) use (& $i, & $category, & $cSql) {
			$name = $node->filter('dt a')->text();
            $code = Pinyin::trans($name, ['delimiter' => '', 'accent' => false]);
			$cSql .= "('{$name}', '{$code}', '{$name}', ''),\n";

			$i++;
            $node->filter('dd a')->each(function ($subNode) use (& $i, $code, & $cSql) {
				$subName = $subNode->text();
                $subCode = Pinyin::trans($subName, ['delimiter' => '', 'accent' => false]);
			    $cSql .= "('{$subName}', '{$subCode}', '{$subName}', '{$code}'),<br />\n";
				$i++;
			});
		});
		echo $cSql;exit();
    }
}

namespace bench\spider\models\crawler\jm91;

use Overtrue\Pinyin\Pinyin;

Trait JmfirstInfoTrait
{
	public function _infoJmfirstJminfo($crawler, $commoninfo)
	{
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, 'info');
        $info = $targetModel->getInfo($commoninfo->target_id);
		$description = $crawler->filter('.news-main-dis')->html();
		$content = $crawler->filter('.news-main-info')->html();
        $aDatas = []; 
		$crawler->filter('.news-main-info img')->each(function ($subNode) use (& $aDatas, $commoninfo, $info) {
			$aData = [
                'source_id' => $commoninfo['source_id'],
				'source_url' => trim($subNode->attr('src')),
                'info_table' => 'info',
                'info_field' => 'picture',
                'info_id' => $info['id'],
				'name' => $info['name'],
			];
			$aDatas[] = $aData;
		});
		$info->description = trim($description);
		$content = $this->_dealContent($content, $aDatas);
		$info->content = $content;
		$info->update(false, ['description', 'content']);
		return true;
	}

	public function _listJmfirstJminfo($crawler, $commonlist)
	{
		$datas = [];
        $crawler->filter('.pj-news-list li')->each(function ($node) use (& $datas, $commonlist) {
            //print_r($node);exit();
            $baseElem = $node->filter('a');
            $sourceUrl = $baseElem->attr('href');
			$name = $baseElem->text('title');
            $sourceId = str_replace('.htm', '', basename($sourceUrl));
			$createdAt = $node->filter('span')->text();
			$createdAt = strtotime($createdAt);
			$datas[] = [
				'source_url' => $sourceUrl,
				'name' => trim($name),
				'code' => $sourceId,
				'created_at' => $createdAt,
				'category_code' => $commonlist['code'],
				'product_id' => $commonlist['code_ext'],
				'source_id' => $sourceId,
			];
		});
		return $datas;
	}
}

namespace bench\spider\models\crawler\jm91;

use Overtrue\Pinyin\Pinyin;

Trait Jm91ShowTrait
{
    public function _infoJm91Productvip($crawler, $commoninfo)
    {
        return $this->_infoJm91Product($crawler, $commoninfo);
    }

    public function _infoJm91Product($crawler, $commoninfo)
    {
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, 'product');
        $product = $targetModel->getInfo($commoninfo->target_id);

        //$dealMerchant = $this->_infoJm91Merchantnew($crawler, $commoninfo, $product);
        //$dealMerchant = $this->_infoJm91Merchant($crawler, $commoninfo, $product);
        //$dealBaseinfo = $this->_infoJm91Baseinfo($crawler, $commoninfo, $product);
        //$dealContent = $this->_infoJm91Content($crawler, $commoninfo, $product);
        //$dealGallery = $this->_infoJm91Gallery($crawler, $commoninfo, $product);
    }

    protected function _infoJm91Gallery($crawler, $commoninfo, $product)
    {
        $elems = $crawler->filter('.picBtnLeft .hd li img');
        $info = [
            'name' => $product['name'],
            'info_table' => 'product',
            'info_field' => 'slide',
            'info_id' => $product['id'],
            'spiderinfo_id' => $this->id,
            'source_site' => $this->site_code,
            'source_id' => $commoninfo['source_id'],
            'path_prefix' => 'default',
            'url_prefix' => 'default',
            'filename' => $product['name'],
            'description' => $product['name'],
            'created_at' => time(),
        ];
            
        $gallerys = [];
        for ($i = 0; $i < count($elems); $i++) {
            $info['source_url'] = $elems->eq($i)->attr('src');
            $this->getPointModel('attachment-bench')->addInfoCheck($info, ['info_id', 'info_table', 'info_field', 'source_url']);
        }
        return true;
    }

    protected function _infoJm91Content($crawler, $commoninfo, $product)
    {
        $elems = $crawler->filter('.col-item .bd .con-title h3');
        $titles = ['加盟介绍'];
        foreach ($elems as $key => $attr) {
            $titles[] = trim($attr->nodeValue);
        }
        $elems = $crawler->filter('.col-item .bd .con-mainContrain-box');
        $contents = [];
        for ($i = 0; $i < count($elems); $i++) {
            $contents[] = $elems->eq($i)->html();
        }
        $fields = [
            '加盟介绍' => 'description',
            '加盟优势' => 'strength',
            '加盟流程' => 'flow',
            '加盟条件' => 'demand',
        ];

        $datas = [];
        foreach ($titles as $key => $tValue) {
            $content = $contents[$key];
            foreach ($fields as $fMark => $field) {
                if (strpos($tValue, $fMark) !== false) {
                    $datas[$field] = $content;
                    $product->$field = $content;
                    continue;
                }
            }
        }
        $product->update(false);
    }

    protected function _infoJm91Baseinfo($crawler, $commoninfo, $product)
    {
        $elems = $crawler->filter('.jm-price-mes .price-list li');
        $elemExts = $crawler->filter('.tb-meta ul li');
        $attrs = [];
        foreach ($elemExts as $key => $attr) {
            $value = trim($attr->nodeValue);
            $value = str_replace(["\r", "\n"], [' ', ' '], $value);
            $attrs[] = $value;
        }
        foreach ($elems as $key => $attr) {
            $value = trim($attr->nodeValue);
            $value = str_replace(["\r", "\n"], [' ', ' '], $value);
            $attrs[] = $value;
        }

        $fields = [
            'store_num' => '门店总数',
            'found_day' => '成立时间',
            'business_model' => '经营模式',
            'invest_area' => '加盟区域',
            'invest_fund' => '投资金额：',
            'investor_fit' => '适合人群：',
            'auth_area' => '区域授权：',
            'develop_model' => '发展模式：',
        ];
        $data = [];
        foreach ($fields as $field => $mark) {
            foreach ($attrs as $value) {
                if (strpos($value, $mark) !== false) {
                    $fValue = trim(str_replace($mark, '', $value));
                    $product->$field = $fValue;
                    $data[$field] = $fValue;
                }
            }
        }

        $product->update(false);
        return true;
    }

    protected function _infoJm91Merchant($crawler, $commoninfo, $product)
    {
        $merchantElem = $crawler->filter('.tb-brandinfo .bd');
        if (count($merchantElem) < 1) {
            echo $commoninfo['source_url'] . '--' . $commoninfo['source_id'] . '<br />';
            return false;
        }
        $mTitle = trim($merchantElem->filter('img')->attr('alt'));
        $mAddress = $merchantElem->filter('.col-meta ul li')->text();
        $mAddress = trim(str_replace('所在地 ', '', $mAddress));


        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, 'merchant');
        $mData = [
            'name' => $mTitle,
            'code' => Pinyin::letter($mTitle, ['delimiter' => '', 'accent' => false]),
            'address' => $mAddress,
        ];
        $merchant = $targetModel->addInfo($mData);

        $attachment = $this->getPointModel('attachment-bench')->getInfo(['where' => ['info_id' => $product['id'], 'info_table' => 'product']]);
        $attachment->info_id = $merchant['id'];
        $attachment->info_table = 'merchant';
        $attachment->update(false, ['info_id', 'info_table']);

        $product->merchant_id = $merchant['id'];
        $product->update(false, ['merchant_id']);
        return true;
    }

    protected function _infoJm91Merchantnew($crawler, $commoninfo, $product)
    {
        $merchantElem = $crawler->filter('.tb-brandinfo .bd');
        if (count($merchantElem) < 1) {
            echo $commoninfo['source_url'] . '--' . $commoninfo['source_id'] . '<br />';
            return false;
        }
        $mTitle = trim($merchantElem->filter('img')->attr('alt'));
        $mThumb = trim($merchantElem->filter('img')->attr('src'));
        $mAddress = $merchantElem->filter('.col-meta ul li')->text();
        $mAddress = trim(str_replace('所在地 ', '', $mAddress));

        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, 'merchant');
        $mData = [
            'name' => $mTitle,
            'code' => Pinyin::letter($mTitle, ['delimiter' => '', 'accent' => false]),
            'address' => $mAddress,
        ];
        $merchant = $targetModel->getInfo(['where' => $mData]);
        $product->merchant_id = $merchant['id'];
        $this->getPointModel('target-model')->getDynamicTable($this->info_db, 'product');
        $r = $product->update(false, ['merchant_id']);
		return ;
        //$merchant = $targetModel->addInfo($mData);

        $aData = [
            'info_table' => 'merchant',
            'info_field' => 'thumb',
            'info_id' => $merchant['id'],
            'source_url' => $mThumb,

            'name' => $product['name'],
            'spiderinfo_id' => $this->id,
            'source_site' => $this->site_code,
            'source_id' => $product['code'],
            'path_prefix' => 'default',
            'url_prefix' => 'default',
            'filename' => $product['name'],
            'description' => $product['name'],
            'created_at' => time(),
        ];

        $attachment = $this->getPointModel('attachment-bench')->addInfoCheck($aData, ['info_table', 'info_field', 'info_id', 'source_url']);
        return true;
    }
}

namespace bench\spider\models\crawler\jm91;

use Overtrue\Pinyin\Pinyin;

Trait Jm91ExtTrait
{
    protected function _listJm91Productshow($crawler, $commonlist)
    {
		$datas = [];
        $crawler->filter('#index-swipe .swiper-stage li')->each(function ($node) use (& $datas, $commonlist) {
			$url = $node->filter('a')->attr('href');
			echo "<a href='{$url}' target='_blank'>{$url}</a><br />";

            $baseElem = $node->filter('a');
            $sourceUrl = $baseElem->attr('href');
			$subNode = $node->filter('img');
			$name = $subNode->attr('alt');
			$thumb = $subNode->attr('src');
            $sourceId = str_replace('.91jm.com', '', basename($sourceUrl));

            $targetModel = $this->getPointModel('target-model')->getDynamicTable('dbLeaguecms', 'product');
			$targetInfo = $targetModel->getInfo($sourceId, 'code');
			$datas[] = [
				'source_url' => $sourceUrl,
				'name' => $name,
				'title' => $name,
				'product_id' => $targetInfo['id'],
				'code' => $sourceId,
				'category_code' => $commonlist['code'],
				'source_id' => $sourceId,
				'thumb' => $thumb,
			];
		});
		return $datas;
	}

    protected function _listJm91Productvip($crawler, $commonlist)
    {
		$datas = [];
        $crawler->filter('.swiper-slide')->each(function ($node) use (& $datas, $commonlist) {
            //print_r($node);exit();
            $baseElem = $node->filter('a');
            $sourceUrl = $baseElem->attr('href');
			$sourceUrl = str_replace('http://vip', 'http://www', $sourceUrl);
			$subNode = $node->filter('img');
			$name = $subNode->attr('alt');
			$thumb = $subNode->attr('src');
            $sourceId = str_replace('.91jm.com', '', basename($sourceUrl));
			$datas[] = [
				'source_url' => $sourceUrl,
				'name' => $name,
				'code' => $sourceId,
				'category_code' => $commonlist['code'],
				'source_id' => $sourceId,
				'thumb' => $thumb,
			];
		});
		return $datas;
    }

	public function _infoJm91Productshow($crawler, $commoninfo)
	{
		return ;
	}
}

namespace bench\spider\models\crawler\jm91;

use Overtrue\Pinyin\Pinyin;

Trait Jm91BaseTrait
{
    protected function _listJm91Product($crawler, $commonlist)
    {
		$datas = [];
        $crawler->filter('.mod-ev-list ul li')->each(function ($node) use (& $datas, $commonlist) {
            //print_r($node);exit();
            $baseElem = $node->filter('a');
            $sourceUrl = $baseElem->attr('href');
			$name = $baseElem->attr('title');
			$name = str_replace('诚邀加盟', '', $name);
            $img = $baseElem->filter('img')->attr('src');
            $sourceId = str_replace('.91jm.com', '', basename($sourceUrl));
			$datas[] = [
				'source_url' => $sourceUrl,
				'name' => $name,
				'code' => $sourceId,
				'category_code' => $commonlist['code'],
				'source_id' => $sourceId,
				'thumb' => $img,
			];
		});
		return $datas;
    }

    protected function _infoTg51Product($crawler, $commoninfo)
    {
        $targetModel = $this->getPointModel('target-model')->getDynamicTable($this->info_db, 'product');
        $product = $targetModel->getInfo($commoninfo->target_id);
        $price = $crawler->filter('.prize_list2_l')->text();
        $product->market_price = trim($price);
        $product->update(false, ['market_price']);
        return true;

        $info = [
            'name' => $product['name'],
            'info_table' => 'product',
            'info_id' => $product['id'],
            'spiderinfo_id' => $this->id,
            'source_site' => $this->site_code,
            'source_id' => $commoninfo['source_id'],
            'filename' => $product['name'],
            'description' => $product['name'],
            'created_at' => time(),
        ];

        $elems = $crawler->filter('.pro_big li img');
        $gallerys = [];
        for ($i = 0; $i < count($elems); $i++) {
            $sourceUrl = $elems->eq($i)->attr('data-original');
            if (empty($sourceUrl)) {
                continue;
            }
            $bData = array_merge($info, [
                'info_field' => 'thumb',
                'source_url' => $sourceUrl,
            ]);
            $this->getPointModel('attachment-bench')->addInfoCheck($bData, ['info_id', 'info_table', 'info_field', 'source_url']);
        }

        $elems = $crawler->filter('.odds_down li img');
        $gallerys = [];
        for ($i = 0; $i < count($elems); $i++) {
            $sourceUrl = $elems->eq($i)->attr('data-original');
            if (empty($sourceUrl)) {
                continue;
            }
            $bData = array_merge($info, [
                'info_field' => 'picture',
                'source_url' => $sourceUrl,
            ]);
            $this->getPointModel('attachment-bench')->addInfoCheck($bData, ['info_id', 'info_table', 'info_field', 'source_url']);
        }
        return true;
    }

    protected function _infoTg51WebsiteGoods($baseInfo, $crawler, $commoninfo)
    {
        $datas = [];
        $spiderinfo = $this->getPointModel('spiderinfo')->getInfo(11);
        $crawler->filter('.all_goods li')->each(function ($node) use (& $datas, $baseInfo, $spiderinfo) {
            $thumb = $node->filter('img')->attr('data-original');
            $name = $node->filter('.all_goods_detail p')->text();
            $sourceUrl = $node->filter('a')->attr('href');
            $sourceId = basename($sourceUrl);

            $data = [
                'source_url' => $sourceUrl,
                'name' => $name,
                'brand_code' => $sourceId,
                'website_id' => $baseInfo['id'],
                'source_id' => $sourceId,
                'thumb' => $thumb,
            ];
            $this->getPointModel('commoninfo')->createRecord($data, $spiderinfo, ['code' => $sourceId]);
        });
        return true;
    }
}

