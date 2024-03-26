<?php
declare(strict_types = 1);

namespace ModuleBench\Services;

use Swoolecan\Foundation\Helpers\DatetimeTool;

class SpiderPageService extends AbstractService
{
    public $urlInfo;
    protected $validExtNames = ['js', 'css', 'png', 'jpg', 'gif', 'ico', 'jpeg', 'eot', 'ttf', 'woff', 'svg', 'cur'];

    public function _dealAssetOperation($info)
    {
        if ($info->name_ext != 'css') {
            return ;
        }

        $file = $this->getPointFile($info->getAssetFile());
        //echo $file . '<br />'; exit();
        if (!file_exists($file)) {
            $info->status = 0;
            $info->save();
            echo 'nono---' . $file . '<br />';
            return ;
        }
        $fileDeal = str_replace('source/css', '', $file);
        if (file_exists($fileDeal)) {
            echo $fileDeal . '---exists<br />';
            return ;
        }

        $content = file_get_contents($file);
        $assets = $this->getAssets($content);
        $rDatas = [];
        //print_r($info->toArray());
        foreach ($assets as $asset) {
            $data = $this->formatFile($asset, $info->url);
            if (empty($data)) {
                continue;
            }
            $aData = $this->getModelObj('asset')->where(['url_base' => $data['url_base'], 'css_id' => $info->id])->first();
            if (empty($aData['code'])) {
                var_dump($data['url_base']);
                continue;
            }
            $rDatas[$asset] = $aData['sort'] == 'page' ? '../img/' . $aData['code'] : '../images/' . $aData['code'];
        }
        $this->createFilePath($fileDeal);
        //print_r($rDatas);exit();
        $contentNew = str_replace(array_keys($rDatas), array_values($rDatas), $content);
        file_put_contents($fileDeal, $contentNew);

        $info->status = 2;
        $info->save();
        return true;
    }

    public function _dealPageOperation($info)
    {
        //$this->urlInfo = parse_url($this->url);
        $file = $this->getPointFile($info->getPageFile());
        echo $file . '<br />';
        if (!file_exists($file)) {
            $info->status = 0;
            $info->save();
            echo $file . '---no<br />';
            return false;
        }
        $fileDeal = str_replace('source', 'html', $file);
        //echo $fileDeal;exit();
        if (file_exists($fileDeal)) {
            echo $fileDeal . '---exists<br />';
            return false;
        }

        $content = file_get_contents($file);
        $assets = $this->getAssets($content);
        $urlPrefix = '{{$staticUrl}}';
        $rDatas = [];
        foreach ($assets as $asset) {
            $data = $this->formatFile($asset, $info->url);
            if (empty($data)) {
                continue;
            }
            $aData = $this->getModelObj('asset')->where(['url_base' => $data['url_base'], 'page_id' => $info->id])->first();
            $rDatas[$asset] = $urlPrefix . $aData['path'];
        }
        //print_r($rDatas);exit();
        $this->createFilePath($fileDeal);
        $contentNew = str_replace(array_keys($rDatas), array_values($rDatas), $content);
        $contentNew = preg_replace("/\n    /", "\n", $contentNew);
        $contentNew = preg_replace("/\n    /", "\n", $contentNew);
        $contentNew = '@php' . "\n" . '$staticUrl = \'http://122.152.209.207:8889/\';' . "\n" . '@endphp' . $contentNew;

        file_put_contents($fileDeal, $contentNew);
        $info->status = 2;
        $info->save();
        return true;
    }

    public function _spiderAssetOperation($info)
    {
        if ($info->name_ext != 'css') {
            return ;
        }
        //$this->urlInfo = parse_url($this->url);

        $file = $this->getPointFile($info->getAssetFile());
        if (!file_exists($file)) {
            $info->status = 0;
            $info->save();
            echo 'nono---' . $file . '<br />';
            return ;
        }

        $pageInfo = $info->pageInfo;
        $content = file_get_contents($file);
        $assets = $this->getAssets($content);
        foreach ($assets as $asset) {
            $data = $this->formatFile($asset, $info->url);
            if (empty($data)) {
                continue;
            }
            $data['page_id'] = $info->page_id;
            $data['sort'] = 'ext';
            $data['css_id'] = $info->id;
            $this->createAssetData($data, $pageInfo);
        }
        $info->status = 2;
        $info->save();
        return true;
    }

    public function _downOperation($info)
    {
        //echo $info['url'] . '<br />';
        $result = $this->_downFile($info->getAssetFile(), $info->url);
        if (!empty($result)) {
            $info->status = 1;
            if ($result !== true) {
                $info->filesize = intval($result);
            }
        } else {
            $info->status = -1;
        }
        $info->save();
        return true;
    }

    public function _spiderPageOperation($info)
    {
        $file = $this->getPointFile($info->getPageFile());
        if (!file_exists($file)) {
            $info->status = 0;
            $info->save;
            echo $file . '---no<br />';
        }

        $content = file_get_contents($file);
        $assets = $this->getAssets($content);
        //print_r($assets);exit();
        foreach ($assets as $asset) {
            $data = $this->formatFile($asset, $info->url);
            if (empty($data)) {
                continue;
            }
            $data['page_id'] = $info->id;
            $data['sort'] = 'page';
            //var_dump($data['url']);
            //continue;
            $this->createAssetData($data, $info);
        }
        $info->status = 1;
        $info->save();
    }

    protected function formatFile($urlRemote, $pageUrl)
    {
        $url = $this->_formatUrl($urlRemote, $pageUrl);
        $urlBase = strpos($url, '?') !== false ? substr($url, 0, strpos($url, '?')) : $url;
        $urlBase = strpos($urlBase, '!') !== false ? substr($urlBase, 0, strpos($urlBase, '!')) : $urlBase;
        $name = basename($urlBase);

        $extName = $this->extNameCheck($name);
        if (!in_array($extName, $this->validExtNames)) {
            //echo $extName . '--' . $name . '--' . $urlRemote . '<br />';
            return false;
        }
        $data = [
            'name_ext' => $extName,
            'name' => $name,
            'url' => $url,
            'url_remote' => $urlRemote,
            'url_base' => $urlBase,
        ];

        return $data;
    }

    protected function _formatUrl($urlBase, $pageUrl)
    {
        $urlBase = str_replace(['"', "'", ' '], ['', '', ''], $urlBase); 
        $url = strpos($urlBase, '//') === 0 ? 'http:' . $urlBase : $urlBase;
        //echo $url . '<br />';
        if (strpos($url, 'http') !== false) {
            return $url;
        }
        /*if (!isset($urlInfo)) {
            echo $this->url;
            print_r($this);
            exit();
        }*/
        $urlInfo = parse_url($pageUrl);
        if (substr($url, 0, 1) == '/') {
            $url = $urlInfo['scheme'] . '://' . $urlInfo['host'] . $url;
            //echo "<a href='{$url}' target='_blank'>{$url}</a>--{$urlBase}=={$this->url}<br />";
            return $url;
        }

        $last = substr($pageUrl, -1);
        $posNum = strripos($pageUrl, '/');
        $baseUrlPre = $posNum == 6 ? $pageUrl : dirname($pageUrl);
        $urlPre = $last == '/' ? $pageUrl : $baseUrlPre . '/';
        if (substr($url, 0, 1) != '.' || substr($url, 0, 2) == './') {
            $url = $urlPre . $url;
            //echo "<a href='{$url}' target='_blank'>{$url}</a>--{$urlBase}==<a href='{$this->url}' target='_blank'>{$this->url}</a><br />";
            return $url;
        }
        for ($i = 0; substr($url, 0, 3) == '../'; $i++) {
            $urlPre = dirname($urlPre);
            $url = substr($url, 3);
        }
        $url = $urlPre . '/' . $url;
        //echo "<a href='{$url}' target='_blank'>{$url}</a>--{$urlBase}==<a href='{$this->url}' target='_blank'>{$this->url}</a><br />";
        return $url;
    }

    protected function extNameCheck($name)
    {
        $dotPos = strripos($name, '.');
        if ($dotPos === false) {
            //echo $name . '--' . $baseUrl . '==' . $urlRemote . '<br />';
            return '';
        }

        $extName = strtolower(substr($name, $dotPos + 1));
        return $extName;
    }

    protected function getAssets($content)
    {
        $patterns = $this->getPatterns();
        $datas = []; 
        foreach ($patterns as $pattern) {
            preg_match_all($pattern, $content, $url);
            if (is_array($url)) {
                $datas = array_merge($datas, $url['url']);
            }
        }
        $datas = array_unique($datas);
        return $datas;
    }
    
    protected function getPatterns()
    {
        $patterns = [ 
            '@data-original="(?P<url>.*)"@Us',
            '@data-source="(?P<url>.*)".*@Us',
            '@src= *"(?P<url>.*)".*@Us',
            "@src= *'(?P<url>.*)'.*@Us",
            '@<link.*type="text/css".*href="(?P<url>.*)".*>@Us',
            "@url\( *'(?P<url>.*)'.*\)@Us",
            '@url\( *"(?P<url>.*)".*\)@Us',
            '@url\((?P<url>.*)\)@Us',
            "@<link.*href='(?P<url>.*\.css)'.*>@Us",
            '@<link.*href="(?P<url>.*)".*>@Us',
            "@<link.*href='(?P<url>.*)'.*>@Us",
        ];

        return $patterns;
    }

    public function createAssetData($data, $pageInfo)
    {
        $info = $this->getModelObj('asset')->where(['url_base' => $data['url_base']])->first();
        if (empty($info)) {
            $data['code'] = $this->_createCode($data);
            $data['path'] = $this->_createPath($data, $pageInfo);
            //var_dump($data);exit();return;
            $data['name'] = isset($data['name']) ? substr($data['name'], 0, 200) : '';
            print_r($data);
            return $this->getModelObj('asset')->create($data);
        }
        /*$info->css_id = $data['css_id'];//$info->code . '<br />';
        echo $info->css_id . '<br />';

        $info->status = 3;
        $info->update(false);*/
    }

    public function _createCode($data)
    {
        return $data['name'];
        if ($data['name_ext'] != 'js') {
            return substr(md5($data['url']), 0, 5) . '-' . rand(1000, 9999) . '.' . $data['name_ext'];
        }

        return strtolower(str_replace('.js', '', $data['name'])) . '.js';
    }

    public function _createPath($data, $pageInfo)
    {
        $path = $pageInfo['site_code'] . '/';
        $path .= $pageInfo['is_mobile'] == 1 ? 'm/' : '';
        if (in_array($data['name_ext'], ['js', 'css'])) {
            $path .= "{$data['name_ext']}/";
        } else {
            $path .= $data['sort'] == 'ext' ? 'images/' : 'img/';
        }

        $pathMid = $path . $data['code'];
        $exist = $this->getModelObj('asset')->where(['path' => $pathMid])->first();
        if (!empty($exist)) {
            $path = $path . rand(10, 99) . '/' . $data['code'];
        } else {
            $path = $path . $data['code'];
        }
        return $path;
    }
}
