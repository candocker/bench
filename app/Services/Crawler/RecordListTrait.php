<?php

declare(strict_types = 1);

namespace ModuleBench\Services\Crawler;

trait RecordListTrait
{
	protected function _recordGuoxueGuoxue()
	{
        $datas = [
            //['page' => 1, 'name' => '诗经', 'code' => 'shijing', 'url' => 'http://www.guoxuemeng.com/guoxue/shijing/'],
            ['page' => 1, 'name' => '墨子', 'code' => 'mozi', 'url' => 'http://www.guoxuemeng.com/guoxue/mozi/'],
            ['page' => 1, 'name' => '荀子', 'code' => 'xunzi', 'url' => 'http://www.guoxuemeng.com/guoxue/xunzi/'],
            ['page' => 1, 'name' => '古文观止', 'code' => 'guwenguanzhi', 'url' => 'http://www.guoxuemeng.com/guoxue/guwenguanzhi/'],
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
