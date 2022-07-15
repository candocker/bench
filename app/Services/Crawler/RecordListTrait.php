<?php

declare(strict_types = 1);

namespace ModuleBench\Services\Crawler;

trait RecordListTrait
{
	protected function _recordGuoxueGuoxue()
	{
        $datas = [
            ['page' => 1, 'name' => '诗经', 'code' => 'shijing', 'url' => 'http://www.guoxuemeng.com/guoxue/shijing/'],
        ];

		foreach ($datas as $data) {
			$data['firstUrl'] = str_replace('_{{PAGE}}', '', $data['url']);
		    $this->_writeList($data['url'], $data['page'], $data);
		}
	}

    protected function _recordGushiwenGushiwen()
    {
        $datas = [
            ['page' => 1, 'name' => '楚辞', 'code' => 'chuci', 'url' => 'https://www.gushiju.net/shici/z-%E5%B1%88%E5%8E%9F'],
        ];

		foreach ($datas as $data) {
			$data['firstUrl'] = str_replace('_{{PAGE}}', '', $data['url']);
		    $this->_writeList($data['url'], $data['page'], $data);
		}
    }


	protected function _recordCultureCulture_article()
	{
        $datas = [
            ['page' => 103, 'name' => '书法篆刻', 'code' => 'sfzk', 'url' => 'http://www.yac8.com/news/list_5_{{PAGE}}.html'],
        ];

		foreach ($datas as $data) {
			$data['firstUrl'] = str_replace('_{{PAGE}}', '', $data['url']);
		    $this->_writeList($data['url'], $data['page'], $data);
		}
	}

	protected function _recordPetinfoPet_article()
	{
		$datas = [
            ['page' => 2, 'name' => '小型犬', 'code' => 'xxq', 'url' => 'http://www.boqii.com/pet-all/dog/{{PAGE}}/'],
        ];
		foreach ($datas as $data) {
		    $this->_writeList($data['url'], $data['page'], $data);
		}
	}

	public function _recordXstxtXstxt()
	{
		$datas = [
			//['url' => 'https://www.xstt5.com/gudian/2294/', 'code' => 'guwenguanzhi', 'name' => '古文观止'],
			['url' => 'https://www.xstt5.com/zhuanji/16465/', 'code' => 'kafukaz', 'name' => '卡夫卡传'],
			['url' => 'https://www.xstt5.com/waiwen/16464/', 'code' => 'kafukazdp', 'name' => '卡夫卡中短篇小说选'],
			['url' => 'https://www.xstt5.com/waiwen/1000/', 'code' => 'kafukadp', 'name' => '卡夫卡短篇集'],
			['url' => 'https://www.xstt5.com/waiwen/1341/', 'code' => 'chengbao', 'name' => '城堡'],
			['url' => 'https://www.xstt5.com/waiwen/998/', 'code' => 'shenpan', 'name' => '审判'],
			['url' => 'https://www.xstt5.com/waiwen/9652/', 'code' => 'bianxingji', 'name' => '变形记'],
			['url' => 'https://www.xstt5.com/zhuanji/6671/', 'code' => 'luxunxz', 'name' => '鲁迅像传'],
			['url' => 'https://www.xstt5.com/zhuanji/587/', 'code' => 'lingxiumen', 'name' => '领袖们'],
			['url' => 'https://www.xstt5.com/renwen/3034/', 'code' => 'waijiaoshiji', 'name' => '外交十记'],
			['url' => 'https://www.xstt5.com/renwen/2456/', 'code' => 'zhongguozhexuejs', 'name' => '中国哲学简史'],
			['url' => 'https://www.xstt5.com/gudian/744/', 'code' => 'hongloumeng', 'name' => '红楼梦'],
			['url' => 'https://www.xstt5.com/gudian/7512/', 'code' => 'shijizhu', 'name' => '史记译注'],
			['url' => 'https://www.xstt5.com/gudian/7560/', 'code' => 'yizhuan', 'name' => '易传'],
			['url' => 'https://www.xstt5.com/gudian/2331/', 'code' => 'yijing', 'name' => '易经'],
			['url' => 'https://www.xstt5.com/gudian/4766/', 'code' => 'dongpoyizhuan', 'name' => '东坡易传'],
			['url' => 'https://www.xstt5.com/gudian/1634/', 'code' => 'zhuangzi', 'name' => '庄子'],
			['url' => 'https://www.xstt5.com/gudian/976/', 'code' => 'daodejing', 'name' => '道德经'],
			['url' => 'https://www.xstt5.com/gudian/3332/', 'code' => 'daodejingzhu', 'name' => '道德经译注'],
			['url' => 'https://www.xstt5.com/gudian/901/', 'code' => 'lunyu', 'name' => '论语'],
			['url' => 'https://www.xstt5.com/gudian/3260/', 'code' => 'mengzizhu', 'name' => '孟子译注'],
			['url' => 'https://www.xstt5.com/gudian/3225/', 'code' => 'mengzi', 'name' => '孟子'],
			['url' => 'https://www.xstt5.com/gudian/3268/', 'code' => 'zhuangzizhu', 'name' => '庄子译注'],
			['url' => 'https://www.xstt5.com/gudian/3267/', 'code' => 'mozizhu', 'name' => '墨子译注'],
			['url' => 'https://www.xstt5.com/gudian/7532/', 'code' => 'xunzizhu', 'name' => '荀子译注'],
			['url' => 'https://www.xstt5.com/gudian/3235/', 'code' => 'daxuezhu', 'name' => '大学译注'],
			['url' => 'https://www.xstt5.com/gudian/3226/', 'code' => 'zhongyongzhu', 'name' => '中庸译注'],
		];
		foreach ($datas as $data) {
		    $this->_writeList($data['url'], 1, $data['code'], $data['name']);
		}
	}

	protected function _writeList($urlBase, $pages, $params = [], $pointSpider = null)
	{
        for ($i = 1; $i <= $pages; $i++) {
			$url = ($i == 1 && isset($params['firstUrl'])) ? $params['firstUrl'] : $urlBase;
		    $url = str_replace('{{PAGE}}', $i, $url);
			$rData = [
				'url' => $url,
				'code_ext' => isset($params['code']) ? $params['code'] : $i,
				'code' => $i,
				'name' => isset($params['name']) ? $params['name'] : $i,
				'page' => $i,
			];
			//print_r($rData);
            //$spider = empty($pointSpider) ? $this : $pointSpider;
			$r = $this->getModelObj('commonlist')->createRecord($this->spiderinfo, $rData);
        }   
		return true;
	}
}
