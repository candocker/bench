<?php

declare(strict_types = 1);

namespace ModuleBench\Controllers;

class CommonlistController extends AbstractController
{
    use DealBookTrait;
    use OperationTrait;

    protected $elem = 'list';

    public function operation()
    {
        $action = $this->request->input('action');
        $actions = ['setting', 'spider', 'deal', 'deepdeal', 'dealBook'];
        if (!in_array($action, $actions)) {
            $this->error('操作不存在');
        }

        $method = "_{$action}Operation";
        $model = $this->getModelObj('commonlist');
        $datas = $this->$method($model, $this->request->all());
        return $this->success($datas);
    }

    protected function _dealBookOperation()
    {
        $commonlistId = 1;
        $commonlist = $this->getModelObj('commonlist')->where('id', $commonlistId)->first();
        $bCode = $commonlist['code'];
        $infos = $this->getModelObj('commoninfo')->where(['info_id' => 0, 'list_id' => $commonlistId])->orderBy('id', 'asc')->get();
        //$this->createChapterFile($infos, $bCode); exit();

        $filePre = config('culture.material_path') . "/books/{$bCode}/";
        if (!is_dir(dirname($filePre))) {
            mkdir(dirname($filePre));
        }
        foreach ($infos as $info) {
            if (in_array($info['code'], ['0', '00'])) {
                continue;
            }

            $file = $filePre . $info['code'] . '.php';
            $subInfos = $this->getModelObj('commoninfo')->where(['status' => 2, 'info_id' => $info['id']])->get();
            if (count($subInfos) < 1) {
                $subInfos = [$info];
                $str = $this->formatContent($subInfos);
            } else {
                $extInfo = json_decode($info->content, true);
                $str = $this->formatContent($subInfos, $extInfo);
            }
            //echo $str;exit();
            file_put_contents($file, $str);
        }
        return $this->success();
    }

    public function createChapterFile($infos, $code)
    {
        $chapters = [];
        foreach ($infos as $info) {
            $chapters[$info['sort']][] = ['code' => $info['code'], 'name' => $info['name']];
        }

        $chapterFile = config('culture.material_path') . "/booklist/{$code}.php";
        $catalogueFile = config('culture.material_path') . "/booklist/{$code}_catalogue.php";
        $catalogueStr = $chapterStr = "<?php\nreturn [\n";
        $chapterStr .= "'chapters' => [\n";
        foreach ($chapters as $code => $elems) {
            $chapterStr .= "[\n";
            $chapterStr .= "    'name' => '{$code}',\n";
            $chapterStr .= "    'brief' => '',\n";
            $chapterStr .= "    'infos' => [\n";
            $infoStr = '';
            foreach ($elems as $elem) {
                $infoStr .= "'{$elem['code']}', ";
                $catalogueStr .= "    '{$elem['code']}' => ['code' => '{$elem['code']}', 'name' => '{$elem['name']}', 'brief' => '',],\n";
            }
            $infoStr = trim($infoStr, ', ');
            $chapterStr .= "        {$infoStr}\n";
            $chapterStr .= "    ],\n";
            $chapterStr .= "],\n";
        }
        $chapterStr .= "],\n";

        $chapterStr .= "];";
        $catalogueStr .= "];";
        echo $catalogueStr;
        echo $chapterStr;
        file_put_contents($chapterFile, $chapterStr);
        file_put_contents($catalogueFile, $catalogueStr);
        return true;
    }

    public function formatContent($infos, $extInfo = null)
    {
        $str = "<?php\nreturn [\n";
        $str .= "'chapters' => [\n";

        foreach ($infos as $info) {
            $content = $info->content;
            $datas = json_decode($content, true);
            //print_r($datas);exit();
            $str .= "[\n";
            $space = '    ';
            $str .= $this->getPointStr($datas, $space);
            $str .= "],\n";
        }
        $str .= "],\n";

        if (!empty($extInfo)) {
            $str .= $this->getPointStr($extInfo, '');
        }

        $str .= "];";
        return $str;
    }

    protected function getPointStr($datas, $space)
    {
        $str = '';
        foreach ($datas as $key => $subValue) {
            $str .= "{$space}'{$key}' => [\n";
            foreach ($subValue as $value) {
                $str .= "{$space}    '{$value}',\n";
            }
            $str .= "{$space}],\n";
        }

        return $str;
    }
}
